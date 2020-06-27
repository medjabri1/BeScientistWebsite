<?php


    spl_autoload_register(function ($class_name) {
        include '../Classes/'. $class_name . '.php';
    });

    if(!isset($_GET['key']) || !isset($_GET['token'])) {
        header('Location: /');
        die();
    }

    $key = $_GET['key'];
    $token = $_GET['token'];

    if(User::verifyEmail($key, $token)) {

        //Email verified
        header('Location: /?'. sha1('email_verified'));
        die();
        
    } else {

        //Email not verified
        header('Location: /?'. sha1('problem_verify'));
        die();

    }


?>