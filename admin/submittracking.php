<?php
    if(isset($_POST['submit'])){
        require_once('../database/config.php');
        require_once('../controllers/controllers.php');

        $db_login = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        $fname = $_POST['firstname'];
        $lname = $_POST['lastname'];
        $trackingNumber = $_POST['tracknum'];
        $shipmentType = $_POST['shipmentType'];
        $content = $_POST['content'];
        $shipmentDate = $_POST['sdate'];
        $deliveryDate = $_POST['ddate'];
        $sourceCity = $_POST['scity'];
        $sourceState = $_POST['sstate'];
        $sourceZip = $_POST['szip'];
        $currentCity = $_POST['ccity'];
        $currentState = $_POST['cstate'];
        $currentZip = $_POST['czip'];
        $destinationCity = $_POST['dcity'];
        $destinationState = $_POST['dstate'];
        $destinationZip = $_POST['dzip'];
        $contactNumber = $_POST['contactnumber'];
        $status = $_POST['status'];

        $query = "INSERT INTO tracking SET 
        id= '',
        first_name ='$fname',
        last_name ='$lname',
        tracking_number ='$trackingNumber',
        shipment_type ='$shipmentType',
        content ='$content',
        shipment_date ='$shipmentDate',
        delivery_date ='$deliveryDate',
        source_city ='$sourceCity',
        source_state ='$sourceState',
        source_zip ='$sourceZip',
        current_city ='$currentCity',
        current_state ='$currentState',
        current_zip ='$currentZip',
        destination_city ='$destinationCity',
        destination_state ='$destinationState',
        destination_zip ='$destinationZip',
        contact_number ='$contactNumber',
        parcel_status ='$status'";

        $insert = mysqli_query($db_login, $query);

        if(!$insert){
            die("Connection failed: " . mysqli_connect_error());
        }else {
            echo "<p>New Parcel Added for Tracking</p>";
        }
    }
?>