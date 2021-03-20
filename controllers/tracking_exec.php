<?php
// Start Session
session_start();

require_once('../database/config.php');
require_once('../controllers/controllers.php');

$php_errormsg = array();

$errflag = false;

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$tracking_number = cleanInt($_POST['trackingnumber']);

if($tracking_number == '') {
    $php_errormsg[] = 'Empty tracking field not allowed';
    $errflag = true;
}

if($errflag){
    $_SESSION['ERRMSG_ARR'] = $php_errormsg;
    session_write_close();
    header("location: ../views/tracking.php");
    exit();
}

$query = "SELECT * FROM tracking WHERE num = '$tracking_number'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 1){
    session_regenerate_id();
    $user = mysqli_fetch_assoc($result);
    $_SESSION['USER_NUM'] = $user['num'];
    session_write_close();
    header("location: ../views/tracking_details.php");
    exit();
} else {
    $php_errormsg[] = 'Provide a valid tracking number';
    $errflag = true;
    if($errflag) {
        $_SESSION['ERRMSG_ARR'] = $php_errormsg;
        session_write_close();
        header("location: ../views/tracking.php");
        exit();
    }
};
?>