<?php
session_start();

if($_SESSION['USER_NUM'] == "") {
    header("location: tracking.php");
    exit();
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
    <title>Golf Courrier and Shipment</title>
  </head>
  <body>
<?php
  require('header.php');
?>

<?php
    $num = $_SESSION['USER_NUM'];

    require_once('../database/config.php');
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $query = "SELECT * FROM tracking WHERE num = '$num'";
    $result = mysqli_query($conn, $query);
    if(!$result){
        die("Connection failed: " . mysqli_connect_error());
    }
    if(mysqli_num_rows($result) == 1) {
        while($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $num = $row['num'];
            $shipdate = $row['ship_date'];
            $estimate_date = $row['Estimated_Date'];
            $ship_type =$row['Shipment_type'];
            $content = $row['Content'];
            $receiver = $row['receiver_name'];
            $address = $row['receiver_address'];
            $country = $row['country'];
            $phone = $row['telephone'];
            $status = $row['status'];
        }
    } else {
        echo "o results";
    }
?>
<div class="container">
<div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>Parcel</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Tracking Number</td>
        <td><?php echo $num; ?></td>
      </tr>
      <tr>
        <td>Ship Date</td>
        <td><?php echo $shipdate; ?></td>
      </tr>
      <tr>
        <td>Estimated Delivery Date</td>
        <td><?php echo $estimate_date; ?></td>
      </tr>
      <tr>
        <td>Shipment Type</td>
        <td><?php echo $ship_type; ?></td>
      </tr>
      <tr>
        <td>Parcel Content</td>
        <td><?php echo $content; ?></td>
      </tr>
      <tr>
        <td>Receiver Name</td>
        <td><?php echo $receiver; ?></td>
      </tr>
      <tr>
        <td>Destination Address</td>
        <td><?php echo $address; ?></td>
      </tr>
      <tr>
        <td>Current Location</td>
        <td><?php echo $country; ?></td>
      </tr>
      <tr>
        <td>Contact Number</td>
        <td><?php echo $phone; ?></td>
      </tr>
      <tr>
        <td>Parcel Status</td>
        <td><?php echo $status; ?></td>
      </tr>
    </tbody>
  </table>
  <div>
      <p><a class="btn float-right btn-md bg-primary text-light menu-link" href="tracking.php">Track Another Package</a></p>
  </div>
  </div>
</div>

<?php
  include_once("../views/map.php");
  require_once('footer.php')
?>