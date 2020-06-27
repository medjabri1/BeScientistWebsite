<?php

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $email = $_POST['email'];
    $password = $_POST['password'];

    if(($user = User::findBy($email, 'email')) != null) {
        
        if(sha1($password) == $user->getPassword()) {

            //Email and password match
            
            if($user->getEmailVerified()) {
                // echo "you are signed in Mr : ". $user->getName();
                echo ('Logged in');
            } else {
                //Email not verified
                // echo "Please verify your email first";
                echo ('Email not verified');
            }


        } else {
            //wrong password
            // echo "Password given is wrong";
            echo ('Wrong password');
        }
        
    } else {
        //Email not found
        // echo "Email given is not found";
        echo ('Email not found');
    }

?>