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
    <title>NEXTGEN - Logistics</title>
  </head>
  <body>
      <?php
        include_once('header.php');
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
                <div class="my-1">
                  <?php
                    require_once('../database/config.php');
                    function get_track_details(){
                      $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
                      $query = "SELECT * FROM tracking";
                      $result = mysqli_query($conn, $query);
                      $allParcelList = array();
                      if(!$result){
                          die("Connection failed: " . mysqli_connect_error());
                      }
                      if(mysqli_num_rows($result) >= 0) {
                          while($arrah = mysqli_fetch_assoc($result)) {
                            $allParcelList[] = $arrah;
                          }
                          return $allParcelList;
                      } else {
                          echo "o results";
                      }
                    }
                  ?>
                </div>
                <div class="container mb-5">
                    <div class="row">
                      <div class="col-12">
                        <h3 class="my-1">Parcel Information</h3>
                        <div class="table-responsive border">          
                          <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>Tracking Number</th>
                                <th>Receiver Name</th>
                                <th>Content</th>
                                <th>Contact Number</th>
                                <th>Status</th>
                                <th>Edit</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                $parcel = get_track_details(); 
                                foreach($parcel as $ap) {
                                    $id = $ap['id'];
                                    $num = $ap['tracking_number'];
                                    $receiver = $ap['first_name'] . ' ' . $ap['last_name'];
                                    $content = $ap['content'];
                                    $phone = $ap['contact_number'];  
                                    $status = $ap['parcel_status'];  
                              ?>
                              <tr class="boldder">
                                <td><?php echo $num; ?></td>
                                <td><?php echo $receiver; ?></td>
                                <td><?php echo $content; ?></td>
                                <td><?php echo $phone; ?></td>
                                <td><?php echo $status; ?></td>
                                <td><a href="./edit.php?id=<?php echo $id; ?>">Edit</a></td>
                                <td><a href="./delete.php?id=<?php echo $id; ?>">Delete</a></td>
                              </tr>
                              <?php 
                              } ?>
                            </tbody>
                          </table>
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