<?php

declare(strict_types=1);

require_once __DIR__ . '/razorpay-config.php';

header('Content-Type: application/json; charset=UTF-8');

const VERIFIED_PAYMENT_LOG_FILE = 'payments-log.csv';

function verify_respond(bool $success, string $message, int $statusCode = 200, array $payload = []): void
{
    http_response_code($statusCode);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $payload));
    exit;
}

function verify_field(string $name, int $maxLength = 200): string
{
    $postName = str_replace([' ', '.'], '_', $name);
    $rawValue = $_POST[$name] ?? $_POST[$postName] ?? '';
    $value = trim((string) $rawValue);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';

    return mb_substr($value, 0, $maxLength);
}

function append_verified_payment_log(array $payment): void
{
    $logPath = __DIR__ . '/' . VERIFIED_PAYMENT_LOG_FILE;
    $isNewFile = !is_file($logPath);
    $handle = fopen($logPath, 'ab');

    if (!$handle) {
        error_log('Moscure payment log could not be opened.');
        return;
    }

    if (flock($handle, LOCK_EX)) {
        if ($isNewFile) {
            fputcsv($handle, [
                'verified_at',
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
                'razorpay_order_id',
                'razorpay_payment_id',
                'admin_mail_status',
                'customer_mail_status',
                'ip_address',
            ], ',', '"', '\\');
        }

        fputcsv($handle, [
            $payment['verified_at'],
            $payment['name'],
            $payment['email'],
            $payment['phone'],
            $payment['product'],
            $payment['quantity'],
            $payment['unit_price'],
            $payment['order_total'],
            $payment['address_line_1'],
            $payment['address_line_2'],
            $payment['city'],
            $payment['state'],
            $payment['pincode'],
            $payment['razorpay_order_id'],
            $payment['razorpay_payment_id'],
            $payment['admin_mail_status'],
            $payment['customer_mail_status'],
            $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
        ], ',', '"', '\\');

        flock($handle, LOCK_UN);
    }

    fclose($handle);
}

function razorpay_fetch_order(string $orderId): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('PHP cURL is required to verify Razorpay order details.');
    }

    $ch = curl_init('https://api.razorpay.com/v1/orders/' . rawurlencode($orderId));

    curl_setopt_array($ch, array_replace(moscure_curl_options(), [
        CURLOPT_HTTPGET => true,
    ]));

    $responseBody = curl_exec($ch);
    $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($responseBody === false || $responseBody === '') {
        throw new RuntimeException($curlError !== '' ? $curlError : 'No response from Razorpay.');
    }

    $decoded = json_decode($responseBody, true);

    if (!is_array($decoded)) {
        throw new RuntimeException('Invalid order response from Razorpay.');
    }

    if ($statusCode < 200 || $statusCode >= 300) {
        $message = $decoded['error']['description'] ?? 'Razorpay order lookup failed.';
        throw new RuntimeException((string) $message);
    }

    return $decoded;
}

function format_delivery_address(array $payment): string
{
    $parts = array_filter([
        $payment['address_line_1'],
        $payment['address_line_2'],
        $payment['city'],
        $payment['state'],
        $payment['pincode'],
    ], static fn (string $part): bool => trim($part) !== '');

    return implode(', ', $parts);
}

function admin_payment_email(array $payment): string
{
    $lines = [
        'New Paid Moscure Order',
        '======================',
        '',
        'Customer Details',
        '----------------',
        'Name     : ' . $payment['name'],
        'Email    : ' . $payment['email'],
        'Phone    : ' . $payment['phone'],
        'Address  : ' . format_delivery_address($payment),
        '',
        'Order Details',
        '-------------',
        'Product  : ' . $payment['product'],
        'Quantity : ' . $payment['quantity'],
        'Unit     : ' . moscure_money((int) $payment['unit_price']),
        'Amount   : ' . moscure_money((int) $payment['order_total']),
        '',
        'Payment Details',
        '---------------',
        'Payment ID       : ' . $payment['razorpay_payment_id'],
        'Razorpay Order ID: ' . $payment['razorpay_order_id'],
        'Paid At          : ' . $payment['verified_at'],
        'IP Address       : ' . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown'),
    ];

    return implode("\r\n", $lines);
}

function customer_payment_email(array $payment): string
{
    $lines = [
        'Thank you for your Moscure order!',
        '',
        'Your payment has been received successfully. Here are your order details:',
        '',
        'Order Summary',
        '-------------',
        'Product       : ' . $payment['product'],
        'Quantity      : ' . $payment['quantity'],
        'Unit Price    : ' . moscure_money((int) $payment['unit_price']),
        'Amount Paid   : ' . moscure_money((int) $payment['order_total']),
        'Payment ID    : ' . $payment['razorpay_payment_id'],
        'Order ID      : ' . $payment['razorpay_order_id'],
        'Payment Time  : ' . $payment['verified_at'],
        '',
        'Delivery Details',
        '----------------',
        'Name          : ' . $payment['name'],
        'Phone         : ' . $payment['phone'],
        'Email         : ' . $payment['email'],
        'Address       : ' . format_delivery_address($payment),
        '',
        'We will contact you if any delivery detail needs confirmation.',
        '',
        'Regards,',
        'Team Moscure',
    ];

    return implode("\r\n", $lines);
}

function send_payment_emails(array $payment): array
{
    $customerEmailIsValid = filter_var($payment['email'], FILTER_VALIDATE_EMAIL) !== false;
    $adminSent = moscure_send_mail(
        MOSCURE_ADMIN_EMAIL,
        'Paid Moscure order - ' . $payment['product'] . ' - ' . $payment['razorpay_payment_id'],
        admin_payment_email($payment),
        $customerEmailIsValid ? $payment['email'] : MOSCURE_FROM_EMAIL
    );
    $customerSent = false;

    if ($customerEmailIsValid) {
        $customerSent = moscure_send_mail(
            $payment['email'],
            'Moscure order confirmation - ' . $payment['razorpay_payment_id'],
            customer_payment_email($payment),
            MOSCURE_ADMIN_EMAIL
        );
    }

    if (!$adminSent) {
        moscure_log_razorpay_error('Admin payment email failed for payment ' . $payment['razorpay_payment_id']);
    }

    if (!$customerSent) {
        moscure_log_razorpay_error('Customer payment email failed for payment ' . $payment['razorpay_payment_id']);
    }

    return [
        'admin_mail_status' => $adminSent ? 'sent' : 'failed',
        'customer_mail_status' => $customerSent ? 'sent' : 'failed',
    ];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    verify_respond(false, 'Method not allowed.', 405);
}

$razorpayOrderId = verify_field('razorpay_order_id', 80);
$razorpayPaymentId = verify_field('razorpay_payment_id', 80);
$razorpaySignature = verify_field('razorpay_signature', 160);

if ($razorpayOrderId === '' || $razorpayPaymentId === '' || $razorpaySignature === '') {
    verify_respond(false, 'Missing Razorpay payment details.', 422);
}

$generatedSignature = hash_hmac(
    'sha256',
    $razorpayOrderId . '|' . $razorpayPaymentId,
    RAZORPAY_KEY_SECRET
);

if (!hash_equals($generatedSignature, $razorpaySignature)) {
    verify_respond(false, 'Payment verification failed.', 400);
}

try {
    $razorpayOrder = razorpay_fetch_order($razorpayOrderId);
} catch (Throwable $exception) {
    moscure_log_razorpay_error('Order verification lookup failed: ' . $exception->getMessage());
    verify_respond(false, 'Could not verify Razorpay order amount.', 502);
}

$notes = is_array($razorpayOrder['notes'] ?? null) ? $razorpayOrder['notes'] : [];
$productName = (string) ($notes['product'] ?? verify_field('fav_language', 80));
$product = moscure_product($productName);
$quantity = max(1, min(20, (int) ($notes['quantity'] ?? verify_field('Quantity', 10))));

if ($product === null) {
    verify_respond(false, 'Invalid product in payment details.', 422);
}

$unitPrice = (int) $product['price'];
$orderTotal = $unitPrice * $quantity;
$expectedAmount = $orderTotal * 100;
$actualAmount = (int) ($razorpayOrder['amount'] ?? 0);

if ($actualAmount !== $expectedAmount) {
    verify_respond(false, 'Razorpay order amount does not match the product total.', 400);
}

$payment = [
    'verified_at' => date('Y-m-d H:i:s T'),
    'name' => verify_field('Complete Name', 120),
    'email' => verify_field('Email Address', 120),
    'phone' => verify_field('Phone No', 30),
    'product' => $productName,
    'quantity' => (string) $quantity,
    'unit_price' => (string) $unitPrice,
    'order_total' => (string) $orderTotal,
    'address_line_1' => verify_field('Address Line 1', 250),
    'address_line_2' => verify_field('Address Line 2', 250),
    'city' => verify_field('City', 80),
    'state' => verify_field('State', 80),
    'pincode' => verify_field('Pincode', 10),
    'razorpay_order_id' => $razorpayOrderId,
    'razorpay_payment_id' => $razorpayPaymentId,
    'admin_mail_status' => 'not_sent',
    'customer_mail_status' => 'not_sent',
];

$supabasePayload = [
    'user_id' => null,
    'razorpay_order_id' => $payment['razorpay_order_id'],
    'razorpay_payment_id' => $payment['razorpay_payment_id'],
    'amount_paise' => $expectedAmount,
    'status' => 'confirmed',
    'items' => [
        [
            'sku' => $payment['product'],
            'name' => $product['label'] ?? $payment['product'],
            'qty' => (int) $payment['quantity'],
            'price' => (int) $payment['unit_price']
        ]
    ],
    'address' => [
        'name' => $payment['name'],
        'email' => $payment['email'],
        'phone' => $payment['phone'],
        'line1' => $payment['address_line_1'],
        'line2' => $payment['address_line_2'],
        'city' => $payment['city'],
        'state' => $payment['state'],
        'pincode' => $payment['pincode']
    ]
];

try {
    moscure_insert_supabase('orders', $supabasePayload);
    $payment['admin_mail_status'] = 'supabase_inserted';
    $payment['customer_mail_status'] = 'supabase_inserted';
} catch (Throwable $exception) {
    moscure_log_razorpay_error('Supabase insert failed for payment ' . $payment['razorpay_payment_id'] . ': ' . $exception->getMessage());
    $payment['admin_mail_status'] = 'supabase_failed';
    $payment['customer_mail_status'] = 'supabase_failed';
}

append_verified_payment_log($payment);

verify_respond(true, 'Payment verified successfully.', 200, [
    'admin_mail_status' => $payment['admin_mail_status'],
    'customer_mail_status' => $payment['customer_mail_status'],
    'razorpay_order_id' => $payment['razorpay_order_id'],
    'razorpay_payment_id' => $payment['razorpay_payment_id'],
]);
