<?php
$footerPagePrefix = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/views/') !== false ? '' : 'views/';
?>
<section class="footer-widget-section">
	<div class="container">
		<div class="row">
			<div class="col-sm-4">
				<div class="footer-widget">
					<h3>About</h3>
					<p>NEXTGEN Logistics helps customers explore shipping options, request quotes and check available tracking updates.
					</p>
					<br>

					<address>
						<strong>Customer support</strong>
						<br>
						<a href="<?php echo $footerPagePrefix; ?>contact.php">Contact our team</a>
						<br>
						<br>
						<span class="map-marker">Pickup and delivery coverage depends on your route.</span>
					</address>

					
				</div><!-- /.footer-widget -->
			</div><!-- /.col-sm-4 -->

			<div class="col-sm-4">
				<div class="footer-widget">
					<h3>Useful Links</h3>
					<div class="row">
						<div class="col-sm-6">
							<ul class="quick-links">
								<li><a href="<?php echo $footerPagePrefix; ?>about.php">About us</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>tracking.php">Track a shipment</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>contact.php">Contact</a></li>
								
								
								
								
							</ul>
						</div>
						<div class="col-sm-6">
							<ul class="quick-links">
								<li><a href="<?php echo $footerPagePrefix; ?>services.php">Our services</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>quotation.php">Request a quote</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>contact.php">Customer support</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>about.php">Why NEXTGEN</a></li>
								<li><a href="<?php echo $footerPagePrefix; ?>services.php">Freight services</a></li>
							</ul>
						</div>
					</div>
				</div><!-- /.footer-widget -->
			</div><!-- /.col-md-4 -->

			<div class="col-sm-4">
				<div class="footer-widget">
					<h3>Need shipping help?</h3>
					<p>Have a shipment to move or need an update? Choose the right next step and our team can review your request.</p>
					<p><a class="btn btn-primary" href="<?php echo $footerPagePrefix; ?>quotation.php">Request a quote</a></p>
					<p><a href="<?php echo $footerPagePrefix; ?>tracking.php"><i class="fas fa-route mr-2" aria-hidden="true"></i>Track a shipment</a></p>
					<p><a href="<?php echo $footerPagePrefix; ?>contact.php"><i class="fas fa-headset mr-2" aria-hidden="true"></i>Customer support</a></p>
				</div><!-- /.footer-widget -->
			</div><!-- /.col-md-4 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>
<footer class="copyright-section">
  <div class="container">
    <div class="footer-menu">
      <ul>
        <li><a href="<?php echo $footerPagePrefix; ?>contact.php">Privacy enquiries</a></li>
        <li><a href="<?php echo $footerPagePrefix; ?>about.php">Service information</a></li>
        <li><a href="<?php echo $footerPagePrefix; ?>contact.php">Accessibility support</a></li>
      </ul>
    </div>

    <div class="copyright-info">
      <span>Copyright © <?php echo date("Y"); ?> NEXTGEN Logistics. All rights reserved.<br></span>
    </div>
  </div><!-- /.container -->
</footer>

<!--- Footer --->
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>