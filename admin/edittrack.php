<?php
    $id = $_REQUEST['id'];
    require_once('../database/config.php');
    require_once('../controllers/controllers.php');
    $db_login = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $query = "SELECT * FROM tracking WHERE id = '$id'";
    $result = mysqli_query($db_login, $query);
    if(!$result){
        die("Connection failed: " . mysqli_connect_error());
    }
    while($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $num = $row['tracking_number'];
        $shipdate = $row['shipment_date'];
        $estimate_date = $row['delivery_date'];
        $ship_type =$row['shipment_type'];
        $content = $row['content'];
        $r_firstname = $row['first_name'];
        $r_lastname = $row['last_name'];
        $source_city = $row['source_city'];
        $source_state = $row['source_state'];
        $source_country = $row['source_zip'];
        $current_city = $row['current_city'];
        $current_state = $row['current_state'];
        $current_country = $row['current_zip'];
        $destination_city = $row['destination_city'];
        $destination_state = $row['destination_state'];
        $destination_country = $row['destination_zip'];
        $phone = $row['contact_number'];
        $status = $row['parcel_status'];
    }
?>