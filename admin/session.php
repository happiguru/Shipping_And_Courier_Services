<?php
    session_start();

    require_once('../database/config.php');
    require_once('../controllers/controllers.php');

    $db_login = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    $user_check = $_SESSION['username'];
    $query = "SELECT * FROM admin WHERE username = '$user_check'";
    $result = mysqli_query($db_login, $query);

    $row = mysqli_fetch_assoc($result);
    $login_session = $row['username'];
    if(!isset($login_session)){
        mysqli_close($db_login);
        header('Location: index.php');
    }
?>