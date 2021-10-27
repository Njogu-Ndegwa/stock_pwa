<?php

require_once '../vendor/autoload.php';

require_once 'postMiddleware.php';

use app\User;

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $User = new User();

    $username = $User->sanitiseInput($_POST['username']);

    $password = $User->sanitiseInput($_POST['password']);

    $loginUserResponse = $User->loginUser($username,$password);

    switch ($loginUserResponse['response']) {
        case '200':
            $_SESSION['success'] = $loginUserResponse['data'][0]['username'] . "logged in successfully";

            echo "Success logging in". $loginUserResponse['data'][0]['username'];
            break;
        case '204':
            $_SESSION['error'] = "No matching credentials found!";
            header("Location:". $_SERVER['HTTP_REFERER']);
            exit();
            break;
        default:
            $_SESSION['error'] = "There has been an internal error, this has been recorded and will be resolved.";
            header("Location:". $_SERVER['HTTP_REFERER']);
            exit();
            break;
    }
    
}else{
    $_SESSION['error'] = "Required input to perform login are missing";
    header("Location:". $_SERVER['HTTP_REFERER']);
    exit();
}
