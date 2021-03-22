<?php
    include('session.php');
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
        include_once('../views/header.php');
      ?>
      <div class="container border my-5">
          <div class="row">
              <?php
                include_once('./admin-sidebar.php')
              ?>
              <div class="col-12 col-md-9">
                <h3>Admin Dashboard</h3>
                <p>
                  <?php
                      if($_SESSION['username']){
                         echo 'Welcome ' . $_SESSION['username'] . ' Click here to <a href="logout.php" title="logout">
                        Logout</a>';
                      } else echo "<h1>Please login first .</h1>";
                      ?>
                  
                </p>
              </div>
          </div>
      </div>
<?php
    include_once('../views/footer.php');
?>