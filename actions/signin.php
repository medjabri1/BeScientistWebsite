<?php

    session_start();

    spl_autoload_register(function ($class_name) {
        include '../Classes/'. $class_name . '.php';
    });

    if(isset($_SESSION['user_id'])) {
        if(User::findBy($_SESSION['user_id'])) {
            header('Location: /');
            die();
        }
    }
    
    if(!isset($_POST['signin_email']) && !isset($_POST['signin_password'])) {
        header('Location: /');
        die();
    }

    $email = $_POST['signin_email'];
    $password = $_POST['signin_password'];

    if(($user = User::findBy($email, 'email')) != null) {
        
        if(sha1($password) == $user->getPassword()) {

            //Email and password match
            
            if($user->getEmailVerified()) {
                
                //Email verified
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['name'] = $user->getName();
                $_SESSION['email'] = $user->getEmail();
                header('Location: /home.php');

                // echo "you are signed in Mr : ". $user->getName();

            } else {

                //Email not verified
                // echo "Please verify your email first";
                
                header('Location: /?'. sha1('not_verified'));

            }


        } else {

            //wrong password
            // echo "Password given is wrong";

            header('Location: /?'. sha1('wrong_password'));

        }
        
    } else {

        //Email not found
        // echo "Email given is not found";

        header('Location: /?'. sha1('not_found'));

    }

?>