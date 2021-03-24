<?php
    include('session.php');
    include_once('submittracking.php');
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
        include_once('../views/header.php');
      ?>
      <div class="container border my-5">
          <div class="row">
              <?php
                include_once('./admin-sidebar.php')
              ?>
              <div class="col-12 col-md-9">
                <h3>Add New Tracked Parcel</h3>
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
                      <label for="validationDefault01">Tracking Number</label>
                      <input type="text" name ="tracknum" class="form-control" id="validationDefault01" placeholder="123456789" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                      <label class="mr-sm-2" for="inlineFormCustomSelect">Shipment Type</label>
                      <select class="custom-select mr-sm-2" name ="shipmentType" id="inlineFormCustomSelect">
                        <option selected>Choose...</option>
                        <option value="Air Freight">Air Freight</option>
                        <option value="Marine Freight">Shipping</option>
                        <option value="Land Freight">Land freight</option>
                      </select>
                    </div>
                  </div>
                  <div class="form-row">
                    <label for="exampleFormControlTextarea1">Parcel Content</label>
                    <textarea class="form-control" name ="content" id="exampleFormControlTextarea1" rows="3"></textarea>
                  </div>
                  <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                      <label for="validationDefault01">Shipped Date</label>
                      <input type="date" name ="sdate" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                      <label for="validationDefault02">Expected Delivery Date</label>
                      <input type="date" name ="ddate" class="form-control" required>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                      <label for="validationDefault03">Source City</label>
                      <input type="text" name ="scity" class="form-control" id="validationDefault03" placeholder="City" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault04">Source State</label>
                      <input type="text" name ="sstate" class="form-control" id="validationDefault04" placeholder="State" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault05">Source Country</label>
                      <input type="text" name ="szip" class="form-control" id="validationDefault05" placeholder="Zip">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-md-6 mb-3">
                      <label for="validationDefault03">Current City</label>
                      <input type="text" name ="ccity" class="form-control" id="validationDefault03" placeholder="City" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault04">Current State</label>
                      <input type="text" name ="cstate" class="form-control" id="validationDefault04" placeholder="State" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault05">Current Country</label>
                      <input type="text" name ="czip" class="form-control" id="validationDefault05" placeholder="Zip">
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
                      <input type="text" name ="dzip" class="form-control" id="validationDefault05" placeholder="Zip">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                      <label>Contact Number</label>
                      <input type="text" name ="contactnumber" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                    <label class="mr-sm-2">Parcel Status</label>
                      <select class="custom-select mr-sm-2" name ="status">
                        <option selected>Choose...</option>
                        <option  value="Shipped">Shipped</option>
                        <option value="DElivered">Delivered</option>
                        <option value="Transit">Transit</option>
                        <option value="Packaging">Packaging</option>
                        <option value="Missing Parcel">Missing Parcel</option>
                      </select>
                    </div>
                  </div>
                  <input class="btn btn-primary" name="submit" type="submit" value="Submit Form"/>
                </form>
                </div>
              </div>
          </div>
      </div>
<?php
    include_once('submittracking.php');
    include_once('../views/footer.php');
?>