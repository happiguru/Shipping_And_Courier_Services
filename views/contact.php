<?php

if(isset($_POST['submit'])){
   
  $name=$_REQUEST['name'];
  $email=$_REQUEST['email'];
  $url=$_REQUEST['url'];
  $company=$_REQUEST['organization'];
  $position=$_REQUEST['position'];
  $country=$_REQUEST['country'];
  $messages=$_REQUEST['message'];

  $message=("
  Name: $name,
  Email: $email,
  Website: $url,
  Company: $company,
  Position: $position,
  Country: $country,
  Message: $messages.");

  $to="info@nextgenlogisticsllc.com";
  $subject="FeedBack Form";
  $headers = "From: $email"; 
  $sent = mail($to, $subject, $message, $headers) ; 
  if($sent){
  echo("<center>Your Feedback was sent successfully</center>");
  }else{
  echo("<center>We encountered an error sending your mail</center>");
  }
}
?>
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
    <title>NEXTGEN - Contact Us</title>
  </head>
  <body>
<?php
    require('header.php');
?>
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>Contact</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="#">Home </a></li>
        <li class="active">/ Contact</li>
      </ol>
    </div>
</section>
<section class="contact-wrap section-padding">
  <div class="container">
    <div class="send-feedback">
      <h2>Send us your feedback</h2>
      <form id="contactForm" method="POST">
        <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="name">Name*</label>
              <input id="name" name="name" type="text" class="form-control" required="" placeholder="">
            </div>
          </div>
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="email">E-mail*</label>
              <input id="email" name="email" type="email" class="form-control" required="" placeholder="">
            </div>
          </div><!-- /.col -->
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="url">Website</label>
              <input id="url" name="url" type="text" class="form-control" placeholder="">
            </div>
          </div><!-- /.col -->
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="organization">Organization</label>
              <input id="organization" name="organization" type="text" class="form-control" placeholder="">
            </div>
          </div><!-- /.col -->
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="position">Position</label>
              <input id="position" name="position" type="text" class="form-control" placeholder="">
            </div>
          </div><!-- /.col -->
          <div class="col-12 col-md-4">
            <div class="form-group">
              <label for="country">Country</label>
              <input id="country" name="country" type="text" class="form-control" placeholder="">
            </div>
          </div><!-- /.col -->
            <div class="col-12">
              <div class="form-group">
                  <label>Message</label>
                  <textarea id="message" name="message" class="form-control" rows="2" required="" placeholder=""></textarea>
              </div>
          </div><!-- /.col -->
          <div class="col-12">
          <div class="form-group submit-btn">
            <input type="submit" name="submit" class="btn btn-primary" value="Send Feedback">
          </div>
          </div>
        </div>
      </form>
    </div><!-- /.send-feedback -->
    <div class="office-locations">
      <h2>Worldwide Location</h2>
      <div class="row">
        <div class="col-sm-3 col-xs-6">
          <div class="office-address">
            <h3>USA Office</h3>
            <address>
              <span>2400 W 84th St #7, Hialeah</span>
              <span>FL 33016, United States</span>
              <br>
              <span>T. +1 (203) 442 4225</span><br>
              <span>M.+1 (203) 442 4225</span>
              <a href="#">info@nextgenlogisticsllc.com</a>

            </address>
          </div>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.office-locations -->
  </div><!-- /.container -->
</section>
<?php
    include_once("map.php");
    require_once('footer.php')
?>