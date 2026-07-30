<?php

declare(strict_types=1);

function moscure_load_env(): void
{
    $path = __DIR__ . '/.env';
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        // Remove surrounding quotes if any
        if (preg_match('/^"([^"]*)"$/', $value, $matches) || preg_match('/^\'([^\']*)\'$/', $value, $matches)) {
            $value = $matches[1];
        }

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv("{$name}={$value}");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load environment variables
moscure_load_env();

// Configuration constants
define('RAZORPAY_KEY_ID', getenv('RAZORPAY_KEY_ID') ?: 'rzp_live_TBiBUXa8XSXNqx');
define('RAZORPAY_KEY_SECRET', getenv('RAZORPAY_KEY_SECRET') ?: 'zp6g96Fuvlv0iHk0zNf27XMF');
define('RAZORPAY_CURRENCY', 'INR');
define('RAZORPAY_ERROR_LOG_FILE', 'razorpay-error.log');
define('MOSCURE_ADMIN_EMAIL', 'operations@moscure.com');
define('MOSCURE_FROM_EMAIL', 'operations@moscure.com');
define('MOSCURE_FROM_NAME', 'Moscure Orders');
define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://lrfplgrkhkrkwubncpdu.supabase.co');
define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImxyZnBsZ3JraGtya3d1Ym5jcGR1Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODQ2MjE3NzQsImV4cCI6MjEwMDE5Nzc3NH0.hweHqFUPfHnXI1pwN-bFQ6rtndHezPQCVLJ5wdTcMcg');

function moscure_products(): array
{
    return [
        'Moscure IPO' => [
            'label' => 'Outdoor Unit',
            'price' => 12999,
            'image' => 'assets/img/moscure/ipo-hero.webp',
        ],
        'Moscure IPI' => [
            'label' => 'Indoor Unit',
            'price' => 1299,
            'image' => 'assets/img/moscure/ipi-product.png',
        ],
    ];
}

function moscure_product(string $name): ?array
{
    $products = moscure_products();

    if (!isset($products[$name])) {
        return null;
    }

    return array_merge(['name' => $name], $products[$name]);
}

function moscure_pincode_ranges(): array
{
    static $ranges = null;

    if (is_array($ranges)) {
        return $ranges;
    }

    $ranges = [];
    $path = __DIR__ . '/assets/data/pincode-ranges.json';

    if (!is_file($path)) {
        return $ranges;
    }

    $decoded = json_decode((string) file_get_contents($path), true);

    if (!is_array($decoded) || !isset($decoded['ranges']) || !is_array($decoded['ranges'])) {
        return $ranges;
    }

    foreach ($decoded['ranges'] as $range) {
        if (!is_array($range) || !isset($range['start'], $range['end'])) {
            continue;
        }

        $start = (int) $range['start'];
        $end = (int) $range['end'];

        if ($start > 0 && $end >= $start) {
            $ranges[] = [
                'label' => (string) ($range['label'] ?? ''),
                'start' => $start,
                'end' => $end,
            ];
        }
    }

    return $ranges;
}

function moscure_is_serviceable_pincode(string $pincode): bool
{
    if (!preg_match('/^[0-9]{6}$/', $pincode)) {
        return false;
    }

    $value = (int) $pincode;

    foreach (moscure_pincode_ranges() as $range) {
        if ($value >= $range['start'] && $value <= $range['end']) {
            return true;
        }
    }

    return false;
}

function moscure_format_price(int $price): string
{
    return number_format($price);
}

function moscure_curl_options(): array
{
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
        CURLOPT_TIMEOUT => 30,
    ];
    $caBundle = __DIR__ . '/cacert.pem';

    if (is_file($caBundle)) {
        $options[CURLOPT_CAINFO] = $caBundle;
    }

    return $options;
}

function moscure_log_razorpay_error(string $message): void
{
    error_log('Razorpay: ' . $message);
    @file_put_contents(
        __DIR__ . '/' . RAZORPAY_ERROR_LOG_FILE,
        '[' . date('Y-m-d H:i:s T') . '] ' . $message . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );
}

function moscure_clean_header(string $value): string
{
    return trim(str_replace(["\r", "\n"], '', $value));
}

function moscure_encode_header(string $value): string
{
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

function moscure_send_mail(string $to, string $subject, string $body, string $replyTo = ''): bool
{
    $fromEmail = moscure_clean_header(MOSCURE_FROM_EMAIL);
    $fromName = moscure_clean_header(MOSCURE_FROM_NAME);
    $safeReplyTo = moscure_clean_header($replyTo !== '' ? $replyTo : MOSCURE_FROM_EMAIL);
    $host = preg_replace('/[^A-Za-z0-9.-]/', '', $_SERVER['HTTP_HOST'] ?? 'namebrands.com');
    $messageIdHost = $host !== '' ? $host : 'namebrands.com';
    $headers = [
        'Date: ' . date(DATE_RFC2822),
        'From: ' . moscure_encode_header($fromName) . ' <' . $fromEmail . '>',
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
        moscure_clean_header($to),
        moscure_encode_header($subject),
        $body,
        implode("\r\n", $headers),
        '-f' . $fromEmail
    );
}

function moscure_money(int $amount): string
{
    return 'INR ' . number_format($amount);
}

function moscure_insert_supabase(string $table, array $data): array
{
    if (!function_exists('curl_init')) {
        throw new RuntimeException('PHP cURL is required.');
    }

    $ch = curl_init(SUPABASE_URL . '/rest/v1/' . $table);
    $caBundle = __DIR__ . '/cacert.pem';
    
    $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'apikey: ' . SUPABASE_ANON_KEY,
            'Authorization: Bearer ' . SUPABASE_ANON_KEY,
            'Prefer: return=representation',
        ],
    ];
    
    if (is_file($caBundle)) {
        $options[CURLOPT_CAINFO] = $caBundle;
    }

    curl_setopt_array($ch, $options);

    $responseBody = curl_exec($ch);
    $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($responseBody === false || $responseBody === '') {
        throw new RuntimeException($curlError !== '' ? $curlError : 'No response from Supabase.');
    }

    $decoded = json_decode($responseBody, true);

    if ($statusCode < 200 || $statusCode >= 300) {
        $message = $decoded['message'] ?? 'Supabase insert failed.';
        throw new RuntimeException((string) $message);
    }

    return $decoded;
}
