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
      <div class="container">
        <div class="top-content">
                
                <div class="inner-bgd">
                    <div class="container w-50 mx-auto my-5 border">
                        <div class="row">
                            <div class="col-sm-12 form-box">
                                <div class="form-top">
                                    <div class="form-top-left">
                                        <h3>Login to our site</h3>
                                        <p>Enter your username and password to log on:</p>
                                    </div>
                                    <p>
                                        <?php
                                            if(isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count('ERRMSG_ARR') >0 ){
                                                echo '<p class="alert-danger">' ;
                                                    foreach($_SESSION['ERRMSG_ARR'] as $msg) {
                                                        echo $msg;
                                                    }
                                                echo '</p>';
                                                unset($_SESSION['ERRMSG_ARR']);
                                            }
                                        ?>
                                    </p>
                                    <div class="form-top-right">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                </div>
                                <div class="form-bottom">
                                    <form role="form" action="login-exec.php" name ="login" method="POST" class="login-form">
                                        <div class="form-group">
                                            <label class="sr-only" for="form-username">Username</label>
                                            <input type="text" name="username" placeholder="Username..." class="form-username form-control" id="form-username">
                                        </div>
                                        <div class="form-group">
                                            <label class="sr-only" for="form-password">Password</label>
                                            <input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
                                        </div>
                                        <button type="submit" class="btn btn-md text-white bg-danger">Sign in!</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
      </div>
<?php
    include_once('../views/footer.php');
?>