<?php

    spl_autoload_register(function ($class_name) {
        include '../Classes/'. $class_name . '.php';
    });

    session_start();
    if(isset($_SESSION['user_id'])) {
        if(User::findBy($_SESSION['user_id'])) {
            header('Location: /');
            die();
        }
    }

    if(!isset($_POST['signup_name']) || !isset($_POST['signup_email']) || !isset($_POST['signup_password']) || !isset($_POST['signup_job'])) {
        header('Location: /');
        die();
    }

    $name = $_POST['signup_name'];
    $email = $_POST['signup_email'];
    $password = sha1($_POST['signup_password']);
    $job = $_POST['signup_job'];
    $type = $_POST['signup_type'];

    if(User::findBy($email, 'email') == null) {

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setJob($job);
        $user->setType($type);
        //Temporairement les email vont êtres validées directement suite à des problemes avec les mails avec l'hebergeur
        $user->setEmailVerified(true);

        if(User::addUser($user)) {

            //user added successfully

            //User::sendVerificationEmail($user);
            header('Location: /?'. sha1('user_added'));
            
            die();

        } else {

            //User not added
            header('Location: /?'. sha1('exc_problem'));
            die();

        }

    } else {

        //Email already used by another account
        header('Location: /?'. sha1('email_used'));
        die();

    }


?>