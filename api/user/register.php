<?php

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = sha1($_POST['password']);

    if(User::findBy($email, 'email') == null) {

        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($password);
        $user->setJob("Student");
        $user->setType("A");
        $user->setEmailVerified(true);

        if(User::addUser($user)) {

            //user added successfully
            // User::sendVerificationEmail($user);
            echo ('Registered');
        } else {
            //User not added
            echo ('Serevr error');
        }

    } else {
        //Email already used by another account
        echo ('This email is already used');
    }


?>