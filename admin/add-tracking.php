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
    <link href="assets/admin.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://kit.fontawesome.com/cbdf2b0f0e.js" crossorigin="anonymous"></script>
    <title>NEXTGEN - Logistics</title>
  </head>
  <body class="admin-app">
      <header class="admin-topbar"><a class="admin-brand" href="dashboard.php"><span class="admin-brand-mark"><i class="fa-solid fa-truck-fast"></i></span><span>NEXTGEN <span style="font-weight:500;color:#748198">Logistics</span></span></a><div class="admin-top-right"><span class="admin-live">Administrator workspace</span><a class="admin-icon-btn" href="logout.php" aria-label="Sign out" title="Sign out"><i class="fa-solid fa-arrow-right-from-bracket"></i></a></div></header>
      <div class="admin-layout"><aside class="admin-sidebar"><?php include_once("./admin-sidebar.php"); ?></aside><main class="admin-main"><div class="admin-heading"><div><p class="admin-eyebrow">Shipment management</p><h1>Create a shipment</h1><p class="admin-subtitle">Add parcel details and tracking milestones for your customer.</p></div><a class="admin-btn admin-btn-light" href="dashboard.php"><i class="fa-solid fa-arrow-left"></i> Back to overview</a></div><section class="admin-panel" style="padding:clamp(18px,3vw,30px)">
          <div class="row"><div class="col-12">
                
                <div class="mb-3">
                <form action="submittracking.php" method="POST"><input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                  <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                      <label for="validationDefault01">First name</label>
                      <input type="text" name ="firstname" class="form-control"  placeholder="Mark" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                      <label for="validationDefault02">Last name</label>
                      <input type="text" name ="lastname" class="form-control"  placeholder="Otto" required>
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                      <label for="validationDefault01">Tracking Number</label>
                      <input type="text" name="tracknum" class="form-control" id="trackingNumberPreview" value="Generated when saved" readonly aria-describedby="trackingNumberHelp"><small id="trackingNumberHelp" class="form-text text-muted">A unique tracking ID is generated securely when you save this shipment.</small>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                      <label class="mr-sm-2" for="inlineFormCustomSelect">Shipment Type</label>
                      <select class="custom-select mr-sm-2" name ="shipmentType" id="shipmentType" required>
                        <option value="" selected disabled>Choose...</option>
                        <option value="Air Freight">Air Freight</option>
                        <option value="Marine Freight">Marine Freight</option>
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
                      <input type="text" name ="scity" class="form-control"  placeholder="City" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault04">Source State</label>
                      <input type="text" name ="sstate" class="form-control"  placeholder="State" required>
                    </div>
                    <div class="col-md-3 mb-3">
                      <label for="validationDefault05">Source Country</label>
                      <input type="text" name ="szip" class="form-control"  placeholder="Country">
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
                      <input type="text" name ="czip" class="form-control" id="validationDefault05" placeholder="Country">
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
                      <input type="text" name ="dzip" class="form-control" id="validationDefault05" placeholder="Country">
                    </div>
                  </div>
                  <div class="form-row">
                    <div class="col-12 col-md-6 mb-3">
                      <label>Contact Number</label>
                      <input type="text" name ="contactnumber" class="form-control" required>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                    <label class="mr-sm-2">Parcel Status</label>
                      <select class="custom-select mr-sm-2" name ="status" id="parcelStatus" required>
                        <option value="" selected disabled>Choose...</option>
                        <option  value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Transit">Transit</option>
                        <option value="Packaging">Packaging</option>
                        <option value="Missing Parcel">Missing Parcel</option>
                        <option value="On Hold">On Hold</option>
                      </select>
                    </div>
                  </div>
                  <input class="btn btn-primary" name="submit" type="submit" value="Submit Form"/>
                </form>
                </div>
              </div>
          </div>
      </section></main></div>
<script>document.querySelectorAll(".admin-sidebar a").forEach(a=>{if(a.href.includes("add-tracking.php"))a.classList.add("active")});</script>
</body></html>