<?php

declare(strict_types=1);

require_once __DIR__ . '/razorpay-config.php';

header('Content-Type: application/json; charset=UTF-8');

function respond_json(bool $success, array $payload = [], int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode(array_merge(['success' => $success], $payload));
    exit;
}

function post_field(string $name, int $maxLength = 200): string
{
    $postName = str_replace([' ', '.'], '_', $name);
    $rawValue = $_POST[$name] ?? $_POST[$postName] ?? '';
    $value = trim((string) $rawValue);
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value) ?? '';

    return mb_substr($value, 0, $maxLength);
}

function razorpay_create_order(array $payload): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('PHP cURL is required to create Razorpay orders.');
    }

    $ch = curl_init('https://api.razorpay.com/v1/orders');

    curl_setopt_array($ch, array_replace(moscure_curl_options(), [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
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
        throw new RuntimeException('Invalid response from Razorpay.');
    }

    if ($statusCode < 200 || $statusCode >= 300) {
        $message = $decoded['error']['description'] ?? 'Razorpay order creation failed.';
        throw new RuntimeException((string) $message);
    }

    return $decoded;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond_json(false, ['message' => 'Method not allowed.'], 405);
}

$productName = post_field('fav_language', 80);
$quantity = max(1, min(20, (int) post_field('Quantity', 10)));
$product = moscure_product($productName);
$customerName = post_field('Complete Name', 120);
$customerPhone = preg_replace('/\D/', '', post_field('Phone No', 30)) ?? '';
$customerEmail = post_field('Email Address', 120);
$addressLine1 = post_field('Address Line 1', 160);
$addressLine2 = post_field('Address Line 2', 160);
$city = post_field('City', 80);
$state = post_field('State', 80);
$pincode = post_field('Pincode', 10);

if ($product === null) {
    respond_json(false, ['message' => 'Please select a valid product.'], 422);
}

if (
    !preg_match('/^[A-Za-z][A-Za-z\s.\'-]{1,119}$/', $customerName)
    || !preg_match('/^[6-9][0-9]{9}$/', $customerPhone)
    || filter_var($customerEmail, FILTER_VALIDATE_EMAIL) === false
    || !preg_match('/^[A-Za-z0-9][A-Za-z0-9\s,.\/#-]{5,159}$/', $addressLine1)
    || ($addressLine2 !== '' && !preg_match('/^[A-Za-z0-9\s,.\/#-]{1,160}$/', $addressLine2))
    || !preg_match('/^[A-Za-z][A-Za-z\s.\'-]{1,79}$/', $city)
    || $state === ''
) {
    respond_json(false, ['message' => 'Please enter valid delivery details.'], 422);
}

if (!moscure_is_serviceable_pincode($pincode)) {
    respond_json(false, ['message' => 'We are not available in your area.'], 422);
}

$unitPrice = (int) $product['price'];
$totalRupees = $unitPrice * $quantity;
$amountPaise = $totalRupees * 100;
$receipt = 'moscure_' . substr(bin2hex(random_bytes(12)), 0, 24);

$notes = [
    'product' => $productName,
    'quantity' => (string) $quantity,
    'unit_price' => (string) $unitPrice,
    'order_total' => (string) $totalRupees,
    'customer_name' => $customerName,
    'customer_phone' => $customerPhone,
    'customer_email' => $customerEmail,
    'city' => $city,
    'state' => $state,
    'pincode' => $pincode,
];

try {
    $order = razorpay_create_order([
        'amount' => $amountPaise,
        'currency' => RAZORPAY_CURRENCY,
        'receipt' => $receipt,
        'notes' => $notes,
    ]);

    respond_json(true, [
        'key' => RAZORPAY_KEY_ID,
        'order_id' => $order['id'] ?? '',
        'amount' => $amountPaise,
        'currency' => RAZORPAY_CURRENCY,
        'product' => [
            'name' => $productName,
            'label' => $product['label'],
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'total' => $totalRupees,
        ],
    ]);
} catch (Throwable $exception) {
    moscure_log_razorpay_error('Order creation failed: ' . $exception->getMessage());
    respond_json(false, ['message' => 'Could not start Razorpay payment. Please try again.'], 502);
}
