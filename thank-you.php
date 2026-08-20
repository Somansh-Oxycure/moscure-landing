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
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5FPLF3W6');</script>
<!-- End Google Tag Manager -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-BKBNG530DK"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-BKBNG530DK');
</script>
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
  <!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1906315613652195');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1906315613652195&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
</head>
<body class="moscure-thankyou-page">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FPLF3W6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
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
