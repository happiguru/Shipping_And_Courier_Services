<?php
				   
  if(isset($_REQUEST['submit'])){

    $fname=$_REQUEST['firstname'];
    $lname=$_REQUEST['lastname'];
    $email=$_REQUEST['email'];
    $priphone=$_REQUEST['contactnumber'];
    $pickaddress=$_REQUEST['paddress'];
    $delivaddress=$_REQUEST['daddress'];
    $pickdate=$_REQUEST['pdate'];
    $picktime=$_REQUEST['ptime'];
    $deliverydate=$_REQUEST['ddate'];
    $pickcity=$_REQUEST['pcity'];
    $pickstate=$_REQUEST['pstate'];
    $pickcountry=$_REQUEST['pcountry'];
    $delicity=$_REQUEST['dcity'];
    $delistate=$_REQUEST['dstate'];
    $delicountry=$_REQUEST['dcountry'];
    $contname=$_REQUEST['receivername'];
    $delitype=$_REQUEST['shipmentType'];
    $vehitype=$_REQUEST['pickupType'];
    $conpackage=$_REQUEST['content'];

    $message=("Frist Name: $fname,
        Last Name: $lname,
        Primary Phone: $priphone,
        Email: $email,
        Pick Up Date: $pickdate,
        Pick Up Time: $picktime,
        Pick Up Address: $pickaddress,
        Pick Up City: $pickcity,
        Pick Up State: $pickstate,
        Pick Up Country: $pickcountry,
        Delivery Date: $deliverydate,
        Delivery Address: $delivaddress,
        Delivery City: $delicity,
        Delivery State: $delistate,
        Delivery Country: $delicountry,
        Vehicle Type: $vehitype,
        Type of Delivery: $delitype,
        Delivery Contact Name: $contname,
        Contents of Package: $conpackage.");

    $to="info@nextgenlogisticsllc.com";
    $subject="Request Form";
    $headers = "From: $email"; 
    $sent = mail($to, $subject, $message, $headers) ; 
    if($sent){
    echo("<center>Your Request was sent successfully</center>");
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
    <title>NEXTGEN - Ask A Quote</title>
  </head>
  <body>
<?php
    require('header.php');
?>
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>Request a Quote</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="#">Home </a></li>
        <li class="active">/ Quote</li>
      </ol>
    </div>
</section>
<div class="container my-4">
  <div class="row">
  <div class="col-12 col-md-9">
    <h2>Request a quote!</h2>
    <div class="">
    <form method="POST">
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault01">First name</label>
          <input type="text" name ="firstname" class="form-control" id="validationDefault01" placeholder="Mark" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault02">Last name</label>
          <input type="text" name ="lastname" class="form-control" id="validationDefault02" placeholder="Otto" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label>Email Address</label>
          <input type="email" name ="email" class="form-control" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label>Contact Number</label>
          <input type="text" name ="contactnumber" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault01">Pickup Address</label>
          <input type="text" name ="paddress" class="form-control" id="validationDefault01" placeholder="Oslo, 12 Street" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault02">Delivery Address</label>
          <input type="text" name ="daddress" class="form-control" id="validationDefault02" placeholder="Port Harcort" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Pick Up Date</label>
          <input type="date" name ="pdate" class="form-control" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Pick Up Time</label>
          <input type="time" name ="ptime" class="form-control" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault02">Expected Delivery Date</label>
          <input type="date" name ="ddate" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
          <label for="validationDefault03">Pick Up City</label>
          <input type="text" name ="pcity" class="form-control" id="validationDefault03" placeholder="City" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault04">Pick Up State</label>
          <input type="text" name ="pstate" class="form-control" id="validationDefault04" placeholder="State" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault05">Pick Up Country</label>
          <input type="text" name ="pcountry" class="form-control" id="validationDefault05" placeholder="Country">
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
          <label for="validationDefault03">Destination City</label>
          <input type="text" name ="dcity" class="form-control" id="validationDefault03" placeholder="City" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault04">Destination State</label>
          <input type="text" name ="dstate" class="form-control" id="validationDefault04" placeholder="State" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault05">Destination Country</label>
          <input type="text" name ="dcountry" class="form-control" id="validationDefault05" placeholder="Country">
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Receiver's Name</label>
          <input type="text" name ="receivername" class="form-control" id="validationDefault01" placeholder="John Doe" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label class="mr-sm-2" for="inlineFormCustomSelect">Shipment Type</label>
          <select class="custom-select mr-sm-2" name ="shipmentType" id="inlineFormCustomSelect">
            <option selected>Choose...</option>
            <option value="Air Freight">Air Freight</option>
            <option value="Marine Freight">Shipping</option>
            <option value="Land Freight">Land freight</option>
          </select>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label class="mr-sm-2" for="inlineFormCustomSelect">Pick Up Vehicle Type</label>
          <select class="custom-select mr-sm-2" name ="pickupType" id="inlineFormCustomSelect">
            <option selected>Choose...</option>
            <option value="Air Freight">Bike</option>
            <option value="Marine Freight">Van</option>
            <option value="Land Freight">Truck</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <label for="exampleFormControlTextarea1">Parcel Content</label>
        <textarea class="form-control" name ="content" id="exampleFormControlTextarea1" rows="3"></textarea>
      </div>
      <div class="form-row mt-3">
        <input class="btn btn-primary" name="submit" type="submit" value="Submit Form"/>
      </div>
    </form>
    </div>
  </div>
  </div>
</div>
<?php
    include_once("map.php");
    require_once('footer.php')
?>