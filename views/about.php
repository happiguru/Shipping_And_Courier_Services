<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.png">
    <!-- Bootstrap CSS -->
    <link href="../css/cssreset.css" rel="stylesheet" />
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/main.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/cbdf2b0f0e.js" crossorigin="anonymous"></script>
    <title>NEXTGEN - About Us</title>
  </head>
  <body>
<?php
    require('header.php');
?>
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>About Us</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="#">Home </a></li>
        <li class="active">/ About</li>
      </ol>
    </div>
</section>
<section class="about-us-intro-wrap section-padding">
  <div class="container">
    <div class="row">
      <div class="col-sm-5">
        <div class="about-thumb">
          <img class="img-responsive" src="../assets/images/about-thumb.jpg" alt="thumb">
        </div>
      </div><!-- /.col -->

      <div class="col-sm-7">
        <div class="about-us-intro-content">
          <div class="section-heading">
            <h2 class="section-title">A few words about us</h2>
          </div>
          <span>NEXTGEN Logistics helps people and businesses move shipments with clear communication, practical service options and a focus on dependable handling.</span>
          <br>
          <br>
          <p>From the first quote to final delivery, our goal is to make shipping easier to understand. We help customers compare available transport options, share shipment details and follow tracking updates when they are available.</p>
          <br>							
          <p>We take a practical, customer-first approach: accurate shipment information, responsive support and clear next steps. Service availability, transit estimates and handling requirements depend on the route and shipment details.</p>
          <br>
          <p>We continue to improve the customer experience across freight enquiries, shipment tracking and quote requests. Contact our team to confirm the options available for your delivery. </p>
        </div><!--/.about-us-intro-content  -->

      </div><!--/.col-->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
<section class="about-us-advantage-wrap section-padding">
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-sm-7 col-xs-12">
        <div class="advantage-left-content">
          <div class="section-heading">
            <h2 class="section-title">Our advantages</h2>
          </div>
          <p>Appropriately foster professional initiatives rather than out-of-the-box growth strategies. Dramatically repurpose fully researched schemas via bleeding-edge best practices. Uniquely simplify diverse expertise with interactive communities. Quickly e-enable sticky process improvements before multimedia based testing procedures. Dramatically productivate maintainable platforms after standardized information.Distinctively engineer e-business methods of empowerment without market-driven partnerships. </p>
          <br>							
          <p>Route-aware service through resource sucking meta-services. Clear communication vis-a-vis team building initiatives. Energistically aggregate leveraged vortals rather than competitive vortals. Distinctively engineer e-business methods of empowerment without market-driven partnerships. Uniquely initiate clicks-and-mortar processes after resource sucking data.Quickly re-engineer diverse ROI with state.</p>
        </div><!-- /.advantage-left-content -->
      </div><!-- /.col -->
      <div class="col-md-4 col-sm-5 col-xs-12">
        <div class="advantage-right-content">
          <div>
            <h3>Clear communication</h3>
            <p>Uniquely initiate clicks-and-mortar processes after resource sucking data.Quickly re-engineer diverse.</p>
          </div>
          <div>
            <h3>Route-aware service</h3>
            <p>Uniquely initiate clicks-and-mortar processes after resource sucking data.Quickly re-engineer diverse ROI.</p>
          </div>
        </div><!-- /.advantage-right-content -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container -->
</section>
<?php
    include_once("map.php");
    require_once('footer.php');
?>