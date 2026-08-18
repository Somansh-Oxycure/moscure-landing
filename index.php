<?php
require_once __DIR__ . '/razorpay-config.php';

$moscureProducts = moscure_products();
$moscureIpo = $moscureProducts['Moscure IPO'];
$moscureIpi = $moscureProducts['Moscure IPI'];
?>
<!DOCTYPE html>
<html lang="en-IN">
<head>
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
  <title>Moscure IPO - Chemical-Free Outdoor Mosquito Protection</title>
  <meta name="description" content="Moscure IPO silently attracts and traps mosquitoes, protecting up to 3,500 sq ft chemical-free. Free shipping, 1-year warranty. Order today.">
  <link rel="icon" href="assets/img/moscure/favicon.png">
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/swiper.css">
  <link rel="stylesheet" href="assets/css/jquery.fancybox.min.css">
  <link rel="stylesheet" href="assets/css/fontawesome.min.css">
  <link rel="stylesheet" href="assets/font/flaticon_mycollection.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/moscure.css?v=checkout-close-outside">
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

<body class="moscure-landing">
  <div class="preloader">
    <img src="assets/img/moscure/moscure-whitelogo.png" alt="Moscure" class="preloader-logo">
    <div class="loader"></div>
  </div>
  <!-- header -->
  <header id="stickyHeader">
    <div class="container">
      <div class="top-bar">
        <div class="logo">
          <a href="#home">
            <img alt="Moscure logo" src="assets/img/moscure/moscure-logo.png" class="moscure-logo">
          </a>
        </div>
        <div class="mobile-header-trust-card">
          <i class="fa-solid fa-users"></i>
          <strong>Trusted by 1000+ businesses &amp; 10,000+ families</strong>
        </div>
        <nav>
          <ul id="mainNav">
            <li><a href="#home">Home</a></li>
            <li><a href="#about">Benefits</a></li>
            <li><a href="#services">How It Works</a></li>
            <li><a href="#pricing">Products</a></li>
            <li><a href="#testimonials">Testimonials</a></li>
            <li><a href="#contact">Checkout</a></li>
          </ul>
        </nav>
        <div class="login">
          <div class="desktop-header-trust-card">
            <i class="fa-solid fa-users"></i>
            <strong>Trusted by 1000+ businesses &amp; 10,000+ families</strong>
          </div>
          <button type="button" class="mobile-menu-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mainNav">
            <i class="fa-solid fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </header>
  <section id="home" class="hero-section" style="background-image: url(assets/img/lins-img.png);">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="hero-text">
            <span>Limited Monsoon Offer</span>
            <h1>Outdoor Evenings. Indoor Nights. <br> <span style="color: #e62d3e">Zero Mosquitoes.</span></h1>
            <div class="hero-mobile-img" aria-hidden="true">
              <img src="assets/img/moscure/ipi-product.png" alt="" class="hero-mobile-ipi">
              <img src="assets/img/moscure/ipo-hero.webp" alt="" class="hero-mobile-ipo">
              <span class="hero-product-tag hero-product-tag-outdoor">Outdoor Unit</span>
              <span class="hero-product-tag hero-product-tag-indoor">Indoor Unit</span>
              <div class="price price-ipi">
                <span>Save &#8377;1,901</span>
                <h5><sup>&#8377;</sup><?php echo moscure_format_price((int) $moscureIpi['price']); ?></h5>
                <del>&#8377;3,200</del>
              </div>
              <div class="price">
                <span>Save &#8377;12,001</span>
                <h5><sup>&#8377;</sup><?php echo moscure_format_price((int) $moscureIpo['price']); ?></h5>
                <del>&#8377;25,000</del>
              </div>
            </div>
            <p>Chemical-free mosquito trapping for balconies, gardens, hotels, restaurants & every room in the house.</p>
            <div class="btnvideo">
              <a href="#pricing" class="btn">Buy Now</a>
              <a href="#pricing" class="btn hero-mobile-discount-btn">Buy Now</a>
              <a href="https://wa.me/919217339695" class="btn hero-mobile-whatsapp-btn" target="_blank" rel="noopener" aria-label="Chat with Moscure on WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
                <span>Chat on WhatsApp</span>
              </a>
              <!-- <div class="video">
                <a data-fancybox="" href="https://www.youtube.com/watch?v=ZbwnU1Rp7aY">
                  <i>
                    <svg width="11" height="17" viewBox="0 0 11 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M11 8.49951L0.5 0.27227L0.5 16.7268L11 8.49951Z" fill="#fff"></path>
                    </svg>
                  </i>
                </a>
              </div> -->
            </div>
            <div class="google-star">
              <div>
                <ul class="stars">
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li>Loved by families across India</li>
                </ul>
              </div>
              <!-- <ul class="social-icon">
                <li><a href="https://www.facebook.com/profile.php?id=61566258460713" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on Facebook"><i class="fa-brands fa-facebook"></i></a></li>
                <li><a href="https://www.linkedin.com/company/moscure/" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></li>
                <li><a href="https://www.instagram.com/mos_cure/" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on Instagram"><i class="fa-brands fa-instagram"></i></a></li>
              </ul> -->
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-img hero-combo-products">
            <img src="assets/img/bg-product.jpg" alt="Moscure indoor and outdoor mosquito traps" class="hero-banner-img">
          </div>
        </div>
      </div>
    </div>
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap1"> -->
  </section>

  <div class="gap trust-badges-section">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card trust-card-featured">
            <div class="trust-card-watermark">01</div>
            <div class="trust-card-icon"><i class="fa-solid fa-droplet"></i></div>
            <h4 class="trust-card-title">Water Resistant</h4>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">02</div>
            <div class="trust-card-icon"><i class="fa-solid fa-ban"></i></div>
            <h4 class="trust-card-title">Chemical Free</h4>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">03</div>
            <div class="trust-card-icon"><i class="fa-solid fa-shield-halved"></i></div>
            <h4 class="trust-card-title">Safe For Kids & Pets</h4>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">04</div>
            <div class="trust-card-icon"><i class="fa-solid fa-bolt"></i></div>
            <h4 class="trust-card-title">3x More Effective</h4>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">05</div>
            <div class="trust-card-icon"><i class="fa-solid fa-certificate"></i></div>
            <h4 class="trust-card-title">1 Year Warranty</h4>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section id="about" class="gap">
    <div class="container">
      <div class="heading">
        <h2>Built for every <span>Space</span></h2>
        <p>One trap works everywhere mosquitoes do — at home and at work.</p>
      </div>
      <div class="row">
        <div class="col-xl-4 col-lg-6">
          <div class="about-text">
            <h4>For Your Home</h4>
            <div class="about-use-grid">
              <div class="about-use-item">
                <i class="fa-solid fa-tree"></i>
                <span>Gardens & lawns</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-chair"></i>
                <span>Balconies & terraces</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-bed"></i>
                <span>Bedrooms & living rooms</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-child"></i>
                <span>Kids' & nursery rooms</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-1">
          <div class="about-img">
            <img src="assets/img/moscure/ipo-product.png" alt="Moscure IPO product" class="moscure-product-shot">
          </div>
        </div>
        <div class="col-xl-4 col-lg-6">
          <div class="about-text">
            <h4>For Your Business</h4>
            <div class="about-use-grid">
              <div class="about-use-item">
                <i class="fa-solid fa-utensils"></i>
                <span>Cafes & restaurants</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-hotel"></i>
                <span>Hotels & resorts</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-house-chimney"></i>
                <span>Farmhouses & banquet lawns</span>
              </div>
              <div class="about-use-item">
                <i class="fa-solid fa-hospital"></i>
                <span>Clinics & co-working spaces</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="pricing" class="gap pricing" style="background-image: url(assets/img/lins-img-1.png);">
    <div class="container">
      <div class="heading">
        <h2>Choose Your <span>Moscure</span></h2>
      </div>
      <div class="row">
        <div class="col-lg-6 col-md-6">
          <div class="pricing-table two moscure-product-pricing moscure-product-pricing-ipo">
            <div class="table-head">
              <span>Outdoor Unit</span>
              <h3>Moscure IPO</h3>
              <p class="pricing-product-subtitle">Best for balconies, gardens, hotels, restaurants &amp; farmhouses</p>
              <h2><sup>&#8377;</sup><?php echo moscure_format_price((int) $moscureIpo['price']); ?></h2>
              <p class="pricing-real-price">Real price: <del>&#8377;25,000</del></p>
            </div>
            <img src="assets/img/moscure/ipo-hero.webp" alt="Moscure IPO outdoor mosquito trap" class="pricing-product-img pricing-product-img-ipo">
            <ul class="liststyle">
              <li>Weatherproof, water-resistant build</li>
              <li>Covers up to 3,500 sq ft*</li>
              <li>100% chemical-free &amp; smoke-free</li>
            </ul>
            <a href="#checkout" class="btn moscure-buy-now" data-product-name="Moscure IPO" data-product-label="<?php echo htmlspecialchars((string) $moscureIpo['label'], ENT_QUOTES, 'UTF-8'); ?>" data-product-price="<?php echo (int) $moscureIpo['price']; ?>" data-product-image="<?php echo htmlspecialchars((string) $moscureIpo['image'], ENT_QUOTES, 'UTF-8'); ?>">Buy Now</a>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="pricing-table moscure-product-pricing moscure-product-pricing-ipi">
            <div class="table-head">
              <span>Indoor Unit</span>
              <h3>Moscure IPI</h3>
              <p class="pricing-product-subtitle">Perfect for bedrooms, kitchens, hotel rooms &amp; offices</p>
              <h2><sup>&#8377;</sup><?php echo moscure_format_price((int) $moscureIpi['price']); ?></h2>
              <p class="pricing-real-price">Real price: <del>&#8377;3,200</del></p>
            </div>
            <img src="assets/img/moscure/ipi-product.png" alt="Moscure IPI indoor mosquito trap" class="pricing-product-img pricing-product-img-ipi">
            <ul class="liststyle">
              <li>Compact, 9W USB-powered</li>
              <li>Covers up to 400 sq ft*</li>
              <li>Safe around kids, pets &amp; food</li>
            </ul>
            <a href="#checkout" class="btn two moscure-buy-now" data-product-name="Moscure IPI" data-product-label="<?php echo htmlspecialchars((string) $moscureIpi['label'], ENT_QUOTES, 'UTF-8'); ?>" data-product-price="<?php echo (int) $moscureIpi['price']; ?>" data-product-image="<?php echo htmlspecialchars((string) $moscureIpi['image'], ENT_QUOTES, 'UTF-8'); ?>">Buy Now</a>
          </div>
        </div>
      </div>
    </div>
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap1"> -->
  </section>

  <div class="gap no-bottom trust-badges-section proof-metrics-section">
    <div class="container">
       <div class="heading">
        <h2>Why Moscure <span>Wins</span></h2>
      </div>
      <div class="row g-4">
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card trust-card-featured">
            <div class="trust-card-watermark">01</div>
            <div class="trust-card-icon"><i class="fa-solid fa-ban"></i></div>
            <h4 class="trust-card-title">0% chemicals</h4>
            <p>vs. toxic sprays & coils</p>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">02</div>
            <div class="trust-card-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            <h4 class="trust-card-title">3,500 sq ft</h4>
            <p>coverage per unit</p>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">03</div>
            <div class="trust-card-icon"><i class="fa-solid fa-clock"></i></div>
            <h4 class="trust-card-title">24/7</h4>
            <p>continuous protection</p>
          </div>
        </div>
        <div class="col-md-4 col-lg col-sm-12">
          <div class="trust-card">
            <div class="trust-card-watermark">04</div>
            <div class="trust-card-icon"><i class="fa-solid fa-mosquito"></i></div>
            <h4 class="trust-card-title">5+ species</h4>
            <p>mosquitoes, flies & more</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <section id="comparison" class="gap moscure-comparison-section">
    <div class="container">
      <div class="heading">
        <h2>How Moscure <span>Compares</span></h2>
        <p>See how Moscure stacks up against common mosquito-control options.</p>
      </div>
      <div class="comparison-table-wrap">
        <table class="moscure-comparison-table">
          <thead>
            <tr>
              <th>Type</th>
              <th>Chemical-free</th>
              <th>Coverage</th>
              <th>Runs 24/7</th>
              <th>Water resistant</th>
            </tr>
          </thead>
          <tbody>
            <tr class="comparison-featured-row">
              <th scope="row">Moscure trap</th>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td>Up to 3,500 sq ft</td>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
            </tr>
            <tr>
              <th scope="row">Electric racket</th>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td>Localised only</td>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td><span class="comparison-status comparison-no">No</span></td>
            </tr>
            <tr>
              <th scope="row">Glue / velcro trap</th>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td>Corners / entry points</td>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td><span class="comparison-status comparison-no">No</span></td>
            </tr>
            <tr>
              <th scope="row">Chemical spray</th>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td>Spot / local area</td>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td><span class="comparison-status comparison-no">No</span></td>
            </tr>
            <tr>
              <th scope="row">Liquid vaporiser</th>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td>Small-medium room</td>
              <td><span class="comparison-status comparison-yes">Yes</span></td>
              <td><span class="comparison-status comparison-no">No</span></td>
            </tr>
            <tr>
              <th scope="row">Mosquito coil</th>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td>Small-medium area</td>
              <td><span class="comparison-status comparison-no">No</span></td>
              <td><span class="comparison-status comparison-no">No</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <section id="testimonials" class="gap position-relative">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="heading two">
            <!-- <span>loved across indian homes</span> -->
            <h2>Trusted by 10000+ <span>business and home users</span></h2>
          </div>
          <div class="clients">
            <ul>
              <li>
                <img src="assets/img/moscure/testimonial-avatar.png" alt="img">
              </li>
              <li>
                <img src="assets/img/moscure/user2.png" alt="img">
              </li>
              <li>
                <img src="assets/img/moscure/user3.png" alt="img">
              </li>
              <li>
                <img src="assets/img/moscure/user4.png" alt="img">
              </li>
              <li>
                <a href="#">
                  <h5>+</h5>
                </a>
              </li>
            </ul>
            <div class="google-star">
              <div>
                <ul class="stars">
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li><i class="fa-solid fa-star"></i></li>
                  <li>(10000+)</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="swiper claintswiper">
            <div class="swiper-wrapper">
              <div class="swiper-slide claint-view">
                <h3>Priya Nair</h3>
                <div class="google-star">
                  <ul class="stars">
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li>( Bengaluru &middot; Independent Home )</li>
                  </ul>
                </div>
                
                <h4>&ldquo; We stopped using our garden after 6pm because of mosquitoes. Two weeks with the Moscure and
                  the kids are back playing outside in the evening. No smell, no smoke &mdash; I don't have to worry
                  about what they're breathing. &rdquo; </h4>
              </div>
              <div class="swiper-slide claint-view">
                <h3>Rahul Deshmukh</h3>
                <div class="google-star">
                  <ul class="stars">
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li>( Pune &middot; Villa )</li>
                  </ul>
                </div>
                
                <h4>&ldquo; My father is 74 and the coil smoke always triggered his cough. This solved two problems at
                  once &mdash; the veranda is mosquito-free and the air is clean. Honestly wish we'd bought it years
                  ago. &rdquo; </h4>
              </div>
              <div class="swiper-slide claint-view">
                <h3>Ankita Sharma</h3>
                <div class="google-star">
                  <ul class="stars">
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li>( Gurugram &middot; Farmhouse )</li>
                  </ul>
                </div>
                
                <h4>&ldquo; Bought it for our farmhouse near Sohna, expecting it to struggle in such a big open lawn.
                  It genuinely covers the whole seating area. Silent, looks premium, and it just works. &rdquo; </h4>
              </div>
              <div class="swiper-slide claint-view">
                <h3>Priya Sharma</h3>
                <div class="google-star">
                  <ul class="stars">
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li>( Bengaluru &middot; Independent House )</li>
                  </ul>
                </div>
                
                <h4>&ldquo; We used to avoid sitting in our backyard after sunset because of mosquitoes. Since getting
                  this, our family spends every evening outside without worrying about bites or fumes. &rdquo; </h4>
              </div>
              <div class="swiper-slide claint-view">
                <h3>Amit Verma</h3>
                <div class="google-star">
                  <ul class="stars">
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li><i class="fa-solid fa-star"></i></li>
                    <li>( Gurugram &middot; Apartment )</li>
                  </ul>
                </div>
                
                <h4>&ldquo; I was tired of mosquito sprays that smelled awful and only worked for a short time. This
                  keeps our balcony comfortable every evening with no smoke or constant refills. &rdquo; </h4>
              </div>
            </div>
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap2"> -->
  </section>

  <section id="contact" class="gap no-top">
    <div class="container">
      <form role="form" class="get-a-quote moscure-checkout-form" id="contact-form" method="post" action="submit-contact.php"
        style="background-image: url(assets/img/lins-img.png);">
        <div class="checkout-form-copy">
          <div class="checkout-form-icon" style="display: flex; align-items: center; justify-content: center;"><i class="fa-solid fa-envelope" style="font-size: 40px; color: #e62d3e;"></i></div>
          <span class="checkout-urgency">We're here to help</span>
          <h3>Get In Touch</h3>
          <p>Have questions about Moscure or need help with your order? Drop us a message and our team will get back to you within 24 hours.</p>
        </div>
        <div class="checkout-form-fields">
            <input type="text" name="Name" placeholder="Complete Name" required="">

            <input type="email" name="Email" placeholder="Email Address" required="">

            <input type="number" name="Phone" placeholder="Phone Number" required="">

            <button type="submit" class="btn"><span>Send Enquiry</span></button>
        </div>
      </form>
    </div>
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap1"> -->
  </section>

  <div class="moscure-marquee-section">
    <div class="swiper moscureMarquee">
      <div class="swiper-wrapper">
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
        <div class="swiper-slide moscure-marquee-slide">1-Year Warranty<i class="fa-solid fa-circle"></i>Doorstep Delivery<i class="fa-solid fa-circle"></i></div>
      </div>
    </div>
  </div>
  <footer class="gap no-bottom" style="background-image: url(assets/img/lins-img.png);">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="heading two">
            <h4>Special monsoon offers are <span>waiting for you!</span></h4>
            <p>Get Your Moscure Today At Your Doorstep.</p>
          </div>
          <!-- <form role="form" class="get-subscribee" id="subscribe-form" method="post">
            <input type="email" name="Email_Address" placeholder="email address" required="">
            <button type="submit" class="btn bk">Subscribe</button>
          </form> -->
          <div class="footer-text">
            <h4>Ph: <a href="callto:+919217339695">+91 92173 39695</a> </h4>
            <h4>Em: <a href="mailto:operations@moscure.com">operations@moscure.com</a> </h4>
          </div>
        </div>
      </div>
      <div class="copy-right">
        <p>© 2026 Moscure. All Rights Reserved.</p>
        <nav class="footer-legal-links" aria-label="Footer legal links">
          <a href="https://moscure.com/warranty/">Warranty Info</a>
          <a href="https://moscure.com/safety/">Safety Guidelines</a>
        </nav>
        <ul class="social-icon">
          <li><a href="https://www.facebook.com/profile.php?id=61566258460713" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on Facebook"><i class="fa-brands fa-facebook"></i></a></li>
          <li><a href="https://www.linkedin.com/company/moscure/" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on LinkedIn"><i class="fa-brands fa-linkedin"></i></a></li>
          <li><a href="https://www.instagram.com/mos_cure/" target="_blank" rel="noopener noreferrer" aria-label="Follow Moscure on Instagram"><i class="fa-brands fa-instagram"></i></a></li>
        </ul>
      </div>
    </div>
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap1"> -->
    <!-- <img src="assets/img/shap1.png" alt="img" class="shap2"> -->
  </footer>
  <a href="https://wa.me/919217339695" class="moscure-whatsapp-float" target="_blank" rel="noopener" aria-label="Chat with Moscure on WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
  </a>
  <div class="moscure-checkout-modal" id="moscureCheckoutModal" aria-hidden="true">
    <div class="moscure-checkout-backdrop" data-checkout-close></div>
    <div class="moscure-checkout-dialog" role="dialog" aria-modal="true" aria-labelledby="checkoutModalTitle">
      <button type="button" class="moscure-checkout-close" data-checkout-close aria-label="Close checkout">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <div class="moscure-checkout-shell">
        <aside class="moscure-checkout-summary">
          <span class="checkout-urgency">Limited Monsoon Offer</span>
          <h3 id="checkoutModalTitle" class="moscure-visually-hidden">Complete Your Moscure Order</h3>
          <div class="moscure-checkout-product-visual">
            <img src="<?php echo htmlspecialchars((string) $moscureIpo['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Moscure IPO product" id="checkoutProductImage">
          </div>
          <div class="moscure-order-mini">
            <span id="checkoutProductLabel">Outdoor Unit</span>
            <strong id="checkoutProductName">Moscure IPO</strong>
          </div>
        </aside>
        <form class="moscure-step-form" id="moscureStepCheckout" action="send-order.php" novalidate>
          <div class="moscure-stepper" aria-label="Checkout steps">
            <div class="moscure-step is-active" data-step-indicator="1">
              <span>1</span>
              <strong>Quantity</strong>
            </div>
            <div class="moscure-step" data-step-indicator="2">
              <span>2</span>
              <strong>Address</strong>
            </div>
            <div class="moscure-step" data-step-indicator="3">
              <span>3</span>
              <strong>Payment</strong>
            </div>
          </div>

          <input type="hidden" name="fav_language" id="checkoutProductInput" value="Moscure IPO">
          <input type="hidden" name="Quantity" id="checkoutQuantityInput" value="1">
          <input type="hidden" name="Unit Price" id="checkoutUnitPriceInput" value="<?php echo (int) $moscureIpo['price']; ?>">
          <input type="hidden" name="Order Total" id="checkoutTotalInput" value="<?php echo (int) $moscureIpo['price']; ?>">

          <div class="moscure-step-panel is-active" data-step-panel="1">
            <div class="moscure-panel-heading">
              <span>Step 1 of 3</span>
              <h4>Select Quantity</h4>
            </div>
            <div class="moscure-quantity-box">
              <img src="<?php echo htmlspecialchars((string) $moscureIpo['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Moscure IPO product" class="moscure-quantity-product-image" id="checkoutQuantityImage">
              <div class="moscure-quantity-details">
                <div>
                  <span id="checkoutQuantityProduct">Moscure IPO</span>
                  <strong id="checkoutUnitPrice">&#8377;<?php echo moscure_format_price((int) $moscureIpo['price']); ?> each</strong>
                </div>
                <div class="moscure-quantity-control" aria-label="Select quantity">
                  <button type="button" data-quantity-change="-1" aria-label="Decrease quantity"><i class="fa-solid fa-minus"></i></button>
                  <input type="number" id="checkoutQuantity" min="1" max="20" value="1" inputmode="numeric" aria-label="Quantity">
                  <button type="button" data-quantity-change="1" aria-label="Increase quantity"><i class="fa-solid fa-plus"></i></button>
                </div>
              </div>
            </div>
          </div>

          <div class="moscure-step-panel" data-step-panel="2">
            <div class="moscure-panel-heading">
              <span>Step 2 of 3</span>
              <h4>Delivery Address</h4>
            </div>
            <div class="moscure-address-grid">
              <label>
                <span>Name</span>
                <input type="text" name="Complete Name" id="checkoutName" autocomplete="name" minlength="2" maxlength="80" pattern="[A-Za-z][A-Za-z\s.'-]{1,79}" title="Enter a valid name using letters and spaces." required>
              </label>
              <label>
                <span>Phone</span>
                <input type="tel" name="Phone No" id="checkoutPhone" autocomplete="tel" inputmode="numeric" pattern="[6-9][0-9]{9}" maxlength="10" title="Enter a valid 10-digit Indian mobile number." required>
              </label>
              <label>
                <span>Email</span>
                <input type="email" name="Email Address" id="checkoutEmail" autocomplete="email" maxlength="120" required>
              </label>
              <label>
                <span>Address Line 1</span>
                <input type="text" name="Address Line 1" id="checkoutAddress1" autocomplete="address-line1" minlength="6" maxlength="140" pattern="[A-Za-z0-9][A-Za-z0-9\s,./#-]{5,139}" title="Enter a complete delivery address." required>
              </label>
              <label>
                <span>Address Line 2</span>
                <input type="text" name="Address Line 2" id="checkoutAddress2" autocomplete="address-line2" maxlength="140" pattern="[A-Za-z0-9\s,./#-]*" title="Use only letters, numbers, spaces, comma, slash, dot, hash or hyphen.">
              </label>
              <label>
                <span>City</span>
                <input type="text" name="City" id="checkoutCity" autocomplete="address-level2" minlength="2" maxlength="60" pattern="[A-Za-z][A-Za-z\s.'-]{1,59}" title="Enter a valid city name." required>
              </label>
              <label>
                <span>State</span>
                <select name="State" id="checkoutState" autocomplete="address-level1" required>
                  <option value="">Select state</option>
                  <option value="Andhra Pradesh">Andhra Pradesh</option>
                  <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                  <option value="Assam">Assam</option>
                  <option value="Bihar">Bihar</option>
                  <option value="Chhattisgarh">Chhattisgarh</option>
                  <option value="Delhi">Delhi</option>
                  <option value="Goa">Goa</option>
                  <option value="Gujarat">Gujarat</option>
                  <option value="Haryana">Haryana</option>
                  <option value="Himachal Pradesh">Himachal Pradesh</option>
                  <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                  <option value="Jharkhand">Jharkhand</option>
                  <option value="Karnataka">Karnataka</option>
                  <option value="Kerala">Kerala</option>
                  <option value="Madhya Pradesh">Madhya Pradesh</option>
                  <option value="Maharashtra">Maharashtra</option>
                  <option value="Manipur">Manipur</option>
                  <option value="Meghalaya">Meghalaya</option>
                  <option value="Mizoram">Mizoram</option>
                  <option value="Nagaland">Nagaland</option>
                  <option value="Odisha">Odisha</option>
                  <option value="Punjab">Punjab</option>
                  <option value="Rajasthan">Rajasthan</option>
                  <option value="Sikkim">Sikkim</option>
                  <option value="Tamil Nadu">Tamil Nadu</option>
                  <option value="Telangana">Telangana</option>
                  <option value="Tripura">Tripura</option>
                  <option value="Uttar Pradesh">Uttar Pradesh</option>
                  <option value="Uttarakhand">Uttarakhand</option>
                  <option value="West Bengal">West Bengal</option>
                  <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                  <option value="Chandigarh">Chandigarh</option>
                  <option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
                  <option value="Ladakh">Ladakh</option>
                  <option value="Lakshadweep">Lakshadweep</option>
                  <option value="Puducherry">Puducherry</option>
                </select>
              </label>
              <label>
                <span>Pincode</span>
                <input type="text" name="Pincode" id="checkoutPincode" autocomplete="postal-code" inputmode="numeric" pattern="[0-9]{6}" minlength="6" maxlength="6" title="Enter a valid 6-digit serviceable pincode." aria-describedby="checkoutDeliveryEstimate" required>
              </label>
            </div>
            <p class="moscure-delivery-estimate" id="checkoutDeliveryEstimate" aria-live="polite"></p>
          </div>

          <div class="moscure-step-panel" data-step-panel="3">
            <div class="moscure-panel-heading">
              <span>Step 3 of 3</span>
              <h4>Secure Payment</h4>
            </div>
            <div class="moscure-payment-card">
              <div>
                <span>Product</span>
                <strong id="checkoutReviewProduct">Moscure IPO</strong>
              </div>
              <div>
                <span>Quantity</span>
                <strong id="checkoutReviewQuantity">1</strong>
              </div>
              <div>
                <span>Delivery To</span>
                <strong id="checkoutReviewAddress">Add address in step 2</strong>
              </div>
            </div>
            <div class="checkout-payment-row moscure-modal-payment-row">
              <span>Payment methods accepted</span>
              <img src="assets/img/moscure/payment-methods.png" alt="UPI, cards and net banking accepted">
            </div>
          </div>

          <p class="moscure-checkout-error" id="checkoutError" aria-live="polite"></p>
          <div class="moscure-total-row">
            <div>
              <span>Total Amount</span>
              <strong id="checkoutTotal">&#8377;<?php echo moscure_format_price((int) $moscureIpo['price']); ?></strong>
            </div>
            <div class="moscure-step-actions">
              <button type="button" class="moscure-back-btn" id="checkoutBack">Back</button>
              <button type="button" class="btn" id="checkoutNext"><span>Continue</span></button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="scroll-percentage"><span id="scroll-percentage-value"></span></div>
  <!-- jQuery -->
  <script src="assets/js/jquery-3.6.0.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="assets/js/swiper.js"></script>
  <script src="assets/js/jquery.fancybox.min.js"></script>
  <script src="assets/js/custom.js"></script>
  <script src="assets/js/sweetalert.min.js"></script>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
  <script src="assets/js/contact.js?v=thank-you-redirect"></script>
</body>
