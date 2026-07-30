<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/razorpay-config.php';

function respond(bool $success, string $message, int $statusCode = 200): void
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => $success,
        'message' => $message,
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Method not allowed', 405);
}

// Log input parameters at the start
file_put_contents(
    __DIR__ . '/hubspot-debug.log',
    sprintf("[%s] Received request: %s\n", date('Y-m-d H:i:s'), json_encode($_POST)),
    FILE_APPEND
);

$name = trim($_POST['Name'] ?? '');
$email = trim($_POST['Email'] ?? '');
$phone = trim($_POST['Phone'] ?? '');

if ($name === '' || $email === '' || $phone === '') {
    respond(false, 'Please fill in all required fields.', 400);
}

$apiKey = getenv('HUBSPOT_API_KEY');
if (!$apiKey) {
    respond(false, 'HubSpot API Key is not configured on the server.', 500);
}

// Split Name into First Name and Last Name
$parts = explode(' ', $name, 2);
$firstName = $parts[0];
$lastName = $parts[1] ?? '';

// Build properties
$properties = [
    'email' => $email,
    'firstname' => $firstName,
    'lastname' => $lastName,
    'phone' => $phone,
    'company' => 'Moscure Landing Page' // Using standard 'company' field as a hint
];

$postData = json_encode([
    'properties' => $properties
]);

$ch = curl_init('https://api.hubapi.com/crm/v3/objects/contacts');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Debug logging
$logMessage = sprintf(
    "[%s] Request: %s\nHTTP Code: %d\nError: %s\nResponse: %s\n\n",
    date('Y-m-d H:i:s'),
    $postData,
    $httpCode,
    $error,
    $response
);
file_put_contents(__DIR__ . '/hubspot-debug.log', $logMessage, FILE_APPEND);

if ($error !== '') {
    respond(false, 'Failed to connect to contact service: ' . $error, 500);
}

if ($httpCode >= 200 && $httpCode < 300) {
    respond(true, 'Your enquiry has been submitted successfully.');
} else {
    $resData = json_decode((string) $response, true);
    if ($httpCode === 409) {
        // Contact already exists
        respond(true, 'Your details are already registered. Thank you!');
    } else {
        $msg = $resData['message'] ?? 'HubSpot API Error (HTTP ' . $httpCode . ')';
        respond(false, 'Unable to submit enquiry: ' . $msg, 400);
    }
}
