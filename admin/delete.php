<?php
    $id = $_REQUEST['id'];
    require_once('../database/config.php');
    $db_login = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $sql = "Delete FROM tracking WHERE id = '$id'";

    $query = @mysqli_query($db_login, $sql);
    if(!$query){
        die("Connection failed: " . mysqli_connect_error());
    } else {
        header("Location: ./dashboard.php"); 
        echo ("<center>deleting successful</center>");
    }
?>