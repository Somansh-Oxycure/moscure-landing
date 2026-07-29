<?php
declare(strict_types=1);

$paymentId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($_GET['payment_id'] ?? ''));
$mailStatus = preg_replace('/[^A-Za-z]/', '', (string) ($_GET['mail'] ?? 'sent'));
$mailMessage = $mailStatus === 'failed'
    ? 'Your order is confirmed. The confirmation email may take a little longer to arrive.'
    : 'A confirmation email has been sent to your mail with your order and payment details.';
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You - Moscure Order Confirmed</title>
  <meta name="description" content="Thank you for your Moscure order. Your payment has been received and your order is confirmed.">
  <link rel="icon" href="assets/img/moscure/favicon.png">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/moscure.css?v=thank-you-page">
  <style>
    .moscure-thankyou-copy h1 {
      font-size: clamp(38px, 6vw, 66px) !important;
    }
  </style>
</head>
<body class="moscure-thankyou-page">
  <main class="moscure-thankyou-wrap">
    <section class="moscure-thankyou-card" aria-labelledby="thankYouTitle">
      <div class="moscure-thankyou-copy">
        <span class="moscure-thankyou-kicker">
          <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
          Payment Successful
        </span>
        <h1 id="thankYouTitle">Thank you for your Moscure order!</h1>
        <p><?php echo htmlspecialchars($mailMessage, ENT_QUOTES, 'UTF-8'); ?></p>

        <?php if ($paymentId !== ''): ?>
          <div class="moscure-thankyou-reference">
            <span>Payment Reference</span>
            <strong><?php echo htmlspecialchars($paymentId, ENT_QUOTES, 'UTF-8'); ?></strong>
          </div>
        <?php endif; ?>

        <div class="moscure-thankyou-actions">
          <a href="index.php" class="moscure-thankyou-btn is-primary">
            <i class="fa-solid fa-house" aria-hidden="true"></i>
            Go to Homepage
          </a>
          <a href="mailto:operations@moscure.com" class="moscure-thankyou-btn is-secondary">
            <i class="fa-solid fa-headset" aria-hidden="true"></i>
            Contact Support
          </a>
        </div>
      </div>

      <div class="moscure-thankyou-visual" aria-hidden="true">
        <img src="assets/img/moscure/thank-you-order.svg" alt="">
      </div>
    </section>
  </main>
</body>
</html>
