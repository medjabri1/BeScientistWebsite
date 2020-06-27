<?php
    
    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['user_id']) || !isset($_POST['name']) || !isset($_POST['email'])) die();

    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $user = User::findBy($user_id);

    //User id not existing
    if($user == null) die();

    if(User::findBy($email, 'email') && ($user->getEmail() != $email)) {

        //New Email already Used by another account 
        echo "Email already used";
        die();

    }

    if($user->getName() != $name) $user->setName($name);
    if($user->getEmail() != $email) $user->setEmail($email);

    if(User::updateUser($user)) {

        //user Updated
        echo "Success : User info updated";
        die();

    } else {

        //User Not updated | Server Issues
        echo "Server issues";
        die();
    }

?>