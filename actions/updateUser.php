<?php
    
    spl_autoload_register(function ($class_name) {
        include '../Classes/'. $class_name . '.php';
    });

    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /');
        die();
    }

    if($_POST['update_submit']) {
        
        $user = User::findBy($_SESSION['user_id']);

        if(User::findBy($_POST['update_email'], 'email') && ($user->getEmail() != $_POST['update_email'])) {

            //New Email already Used by another account 
            header('Location: /home.php/?'. sha1('email_used'));
            die();

        }

        if($user->getName() != $_POST['update_name']) $user->setName($_POST['update_name']);
        if($user->getEmail() != $_POST['update_email']) $user->setEmail($_POST['update_email']);
        if(strtolower($user->getJob()) != strtolower($_POST['update_job'])) $user->setJob($_POST['update_job']);

        if(User::updateUser($user)) {

            //user Updated
            header('Location: /home.php?'. sha1('user_updated'));
            die();

        } else {

            //User Not updated | Server Issues
            header('Location: /home.php/?'. sha1('exc_problem'));
            die();
        }

    } else {

        header('Location: /');
        die();
    
    }

?>