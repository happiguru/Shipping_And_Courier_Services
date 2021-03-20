<?php
    session_start();

    require_once('../database/config.php');
    require_once('../controllers/controllers.php');
    $php_errormsg = array();

    $errflag = false;

    $db_login = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    if(!$db_login) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $username = cleanString($_POST['username']);
    $password = cleanString($_POST['password']);

    if($username == ''){
        $php_errormsg[] = 'Username is required';
        $errflag = true;
    }

    if($password == ''){
        $php_errormsg[] = 'Password is required';
        $errflag = true;
    }

    if($errflag){
        $_SESSION['ERRMSG_ARR'] =  $php_errormsg;
        session_write_close();
        header("location: index.php");
        exit();
    }

    $query = "SELECT * FROM admin WHERE username ='$username' AND together1='$password'";
    $result = mysqli_query($db_login, $query);

    if(mysqli_num_rows($result) == 1) {
        session_regenerate_id();
        $user = mysqli_fetch_assoc($result);
        $_SESSION['USER_ADMIN_ID'] = $user['username'];

        session_write_close();
        header("location: dashboard.php");
        exit();
    } else {
        $php_errormsg[] = 'Provide a valid username and password';
        $errflag = true;

        if($errflag) {
            $_SESSION['ERRMSG_ARR'] = $php_errormsg;
            session_write_close();
            header("location: index.php");
            exit();
        }
    }
?>