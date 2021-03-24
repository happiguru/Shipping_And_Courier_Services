<?php
    session_start();
    unset($_SESSION['SESS_MEMBER_ID']);
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
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>Track A Parcel</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="#">Home </a></li>
        <li class="active">/ Track A Parcel</li>
      </ol>
    </div>
</section>
<div class="container">
    <h2 class="my-3">Know the location of your parcel</h2>
    <p>
        <?php
            if(isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ){
                echo '<p class="alert-danger">' ;
                    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
                        echo $msg;
                    }
                echo '</p>';
                unset($_SESSION['ERRMSG_ARR']);
            }
        ?>
    </p><br>
    <div class="w-50 tracking-form mx-auto border">
        <h3 class="text-center my-4">Enter Tracking Number</h3> <br />
        <form action="../controllers/tracking_exec.php" method="POST" name="tracking" class="form-inline d-flex justify-content-center mb-4">
            <input type="text" class="form-control mb-2 mr-sm-2" name ="trackingnumber" id="inlineFormInputName2" placeholder="001234567" required>
            <button type="submit" class="btn btn-primary mb-2">Track</button>
        </form>
    </div>
</div>

<?php
    include_once("map.php");
    require_once('footer.php')
?>