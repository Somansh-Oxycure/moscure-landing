<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/razorpay-config.php';

const ADMIN_EMAIL = MOSCURE_ADMIN_EMAIL;
const FROM_EMAIL = MOSCURE_FROM_EMAIL;
const FROM_NAME = MOSCURE_FROM_NAME;
const ORDER_LOG_FILE = 'orders-log.csv';

function respond(bool $success, string $message, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
    ]);
    exit;
}

function field(string $name, int $maxLength = 200): string
{
    $postName = str_replace([' ', '.'], '_', $name);
    $rawValue = $_POST[$name] ?? $_POST[$postName] ?? '';
    $value = trim((string) $rawValue);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';

    return mb_substr($value, 0, $maxLength);
}

function clean_header(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function encode_header(string $value): string
{
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

function payment_link_for(string $product): string
{
    return '';
}

function email_message(array $order): string
{
    $lines = [
        'New Moscure reserve/order request',
        '',
        'Name: ' . $order['name'],
        'Email: ' . $order['email'],
        'Phone: ' . $order['phone'],
        'Product: ' . $order['product'],
        'Quantity: ' . $order['quantity'],
        'Unit Price: ' . $order['unit_price'],
        'Order Total: ' . $order['order_total'],
        'Address Line 1: ' . $order['address_line_1'],
        'Address Line 2: ' . $order['address_line_2'],
        'City: ' . $order['city'],
        'State: ' . $order['state'],
        'Pincode: ' . $order['pincode'],
        'Payment Status: ' . $order['payment_status'],
        'Payment Link: ' . $order['payment_link'],
        'Submitted: ' . date('Y-m-d H:i:s T'),
        'IP Address: ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'),
    ];

    return implode("\r\n", $lines);
}

function append_order_log(array $order, string $mailStatus): void
{
    $logPath = __DIR__ . '/' . ORDER_LOG_FILE;
    $isNewFile = !is_file($logPath);
    $handle = fopen($logPath, 'ab');

    if (!$handle) {
        error_log('Moscure order log could not be opened.');
        return;
    }

    if (flock($handle, LOCK_EX)) {
        if ($isNewFile) {
            fputcsv($handle, [
                'submitted_at',
                'name',
                'email',
                'phone',
                'product',
                'quantity',
                'unit_price',
                'order_total',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'pincode',
                'payment_status',
                'payment_link',
                'ip_address',
                'mail_status',
            ], ',', '"', '\\');
        }

        fputcsv($handle, [
            date('Y-m-d H:i:s T'),
            $order['name'],
            $order['email'],
            $order['phone'],
            $order['product'],
            $order['quantity'],
            $order['unit_price'],
            $order['order_total'],
            $order['address_line_1'],
            $order['address_line_2'],
            $order['city'],
            $order['state'],
            $order['pincode'],
            $order['payment_status'],
            $order['payment_link'],
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            $mailStatus,
        ], ',', '"', '\\');

        flock($handle, LOCK_UN);
    }

    fclose($handle);
}

function send_order_mail(string $to, string $subject, string $body, string $replyTo): bool
{
    $fromEmail = clean_header(FROM_EMAIL);
    $fromName = clean_header(FROM_NAME);
    $safeReplyTo = clean_header($replyTo);
    $host = preg_replace('/[^A-Za-z0-9.-]/', '', $_SERVER['HTTP_HOST'] ?? 'namebrands.com');
    $messageIdHost = $host !== '' ? $host : 'namebrands.com';
    $headers = [
        'Date: ' . date(DATE_RFC2822),
        'From: ' . encode_header($fromName) . ' <' . $fromEmail . '>',
        'Sender: <' . $fromEmail . '>',
        'Reply-To: <' . $safeReplyTo . '>',
        'Return-Path: <' . $fromEmail . '>',
        'Message-ID: <moscure-' . bin2hex(random_bytes(12)) . '@' . $messageIdHost . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit',
        'Auto-Submitted: auto-generated',
        'X-Mailer: Moscure Website',
    ];

    // Local development fallback to allow testing without an SMTP server setup
    $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
    if (in_array($remoteAddr, ['127.0.0.1', '::1'], true) || stripos($host, 'localhost') !== false) {
        $logPath = __DIR__ . '/local-emails.log';
        $logContent = "[" . date('Y-m-d H:i:s T') . "] === EMAIL TO: $to ===\nSubject: $subject\nReply-To: $safeReplyTo\nHeaders:\n" . implode("\n", $headers) . "\n\nBody:\n$body\n=====================\n\n";
        @file_put_contents($logPath, $logContent, FILE_APPEND | LOCK_EX);
        return true;
    }

    return @mail(
        $to,
        encode_header($subject),
        $body,
        implode("\r\n", $headers),
        '-f' . $fromEmail
    );
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed.', 405);
}

$name = field('Complete Name');
$email = field('Email Address');
$phone = field('Phone No', 30);
$product = field('fav_language', 60);
$quantity = field('Quantity', 10);
$unitPrice = field('Unit Price', 20);
$orderTotal = field('Order Total', 20);
$addressLine1 = field('Address Line 1', 250);
$addressLine2 = field('Address Line 2', 250);
$city = field('City', 80);
$state = field('State', 80);
$pincode = field('Pincode', 10);
$paymentStatus = field('Payment Status', 80) ?: 'Order details submitted';
$postedPaymentLink = field('Payment Link', 200);
$allowedProducts = ['Moscure IPO', 'Moscure IPI'];
$paymentLink = $postedPaymentLink !== '' ? $postedPaymentLink : payment_link_for($product);
$productInfo = moscure_product($product);
if ($productInfo !== null) {
    $unitPriceNumber = (int) $productInfo['price'];
} else {
    $unitPriceNumber = max(0, (int) $unitPrice);
}
$quantityNumber = max(1, min(20, (int) $quantity));
$orderTotalNumber = $unitPriceNumber * $quantityNumber;

if ($name === '' || $email === '' || $phone === '' || $product === '') {
    respond(false, 'Please fill all required fields.', 422);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid email address.', 422);
}

if (!in_array($product, $allowedProducts, true)) {
    respond(false, 'Please select a valid product.', 422);
}

$order = [
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'product' => $product,
    'quantity' => (string) ($quantityNumber ?: 1),
    'unit_price' => (string) $unitPriceNumber,
    'order_total' => (string) $orderTotalNumber,
    'address_line_1' => $addressLine1,
    'address_line_2' => $addressLine2,
    'city' => $city,
    'state' => $state,
    'pincode' => $pincode,
    'payment_status' => $paymentStatus,
    'payment_link' => $paymentLink,
];

$supabasePayload = [
    'user_id' => null,
    'razorpay_order_id' => null,
    'razorpay_payment_id' => null,
    'amount_paise' => $orderTotalNumber * 100,
    'status' => 'pending',
    'items' => [
        [
            'sku' => $product,
            'name' => $productInfo['label'] ?? $product,
            'qty' => $quantityNumber,
            'price' => $unitPriceNumber
        ]
    ],
    'address' => [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'line1' => $addressLine1,
        'line2' => $addressLine2,
        'city' => $city,
        'state' => $state,
        'pincode' => $pincode
    ]
];

try {
    moscure_insert_supabase('orders', $supabasePayload);

    append_order_log($order, 'supabase_inserted');

    respond(true, 'Order details sent.');
} catch (Throwable $exception) {
    error_log('Moscure order failed to insert to Supabase: ' . $exception->getMessage());
    append_order_log($order, 'supabase_failed');
    respond(false, 'We could not send your order details right now. Please try again.', 500);
}
