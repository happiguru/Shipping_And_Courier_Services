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
    <link href="css/cssreset.css" rel="stylesheet" />
    <link href="css/bootstrap.css" rel="stylesheet" />
    <link href="css/main.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/cbdf2b0f0e.js" crossorigin="anonymous"></script>
    <title>NEXTGEN - Logistics and Shipment</title>
  </head>
  <body>
  
  <header>
	<!--- Navbar --->
	<nav class="navbar navbar-expand-lg">
		<div class="container">
			<a class="navbar-brand text-white" href="index.php"><i class="fas fa-lg fa-shipping-fast mr-2"></i>NEXTGEN Logistics</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nvbCollapse" aria-controls="nvbCollapse">
				<span class="navbar-toggler-icon"></span> 
			</button>
			<div class="collapse navbar-collapse" id="nvbCollapse">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item active pl-1">
						<a class="nav-link" href="../index.php"><i class="fa fa-home fa-fw mr-1"></i>Home</a>
					</li>
					<li class="nav-item pl-1">
						<a class="nav-link" href="views/services.php"><i class="fa fa-th-list fa-fw mr-1"></i>Services</a>
					</li>
					<li class="nav-item pl-1">
						<a class="nav-link" href="views/about.php"><i class="fa fa-info-circle fa-fw mr-1"></i>About Us</a>
					</li>
					<li class="nav-item pl-1">
						<a class="nav-link" href="views/contact.php"><i class="fa fa-phone fa-fw fa-rotate-180 mr-1"></i>Contact Us</a>
					</li>
					<li class="nav-item bg-primary pl-1">
						<a class="nav-link" href="views/tracking.php"><i class="fas fa-route fa-fw mr-1"></i>Track & Trace</a>
					</li>
					<li class="nav-item bg-danger pl-1">
						<a class="nav-link" href="views/quotation.php"><i class="fas fa-info fa-fw mr-1"></i>Request Quote</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<!--# Navbar #-->
	</header>
<?php
    include_once("views/slider.php");
?>
<div class="container mt-5">
  <div class="row">
  <?php
    include_once("views/content.php");
    // include_once("views/sidebar.php");
  ?>
  </div>
</div>
<?php
    include_once("views/map.php");
    include_once("views/footer.php");
?>