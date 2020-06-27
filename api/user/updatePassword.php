<?php
    
    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['user_id']) || !isset($_POST['password_old']) || !isset($_POST['password_new']) || !isset($_POST['password_confirm'])) die();
    
    $user_id = $_POST['user_id'];

    $password_old = $_POST['password_old'];
    $password_new = $_POST['password_new'];
    $password_confirm = $_POST['password_confirm'];

    $user = User::findBy($user_id);

    // User id not existing
    if($user == null) die();

    if(sha1($password_old) != $user->getPassword()) {

        //Old password is Wrong
        echo "Old password is wrong";
        die();

    } else {

        if($password_new != $password_confirm) {
            
            //New Password and Confirmation Password are not matching
            echo "Password confirmation is wrong";
            die();

        } else {
            
            $user->setPassword(sha1($_POST['password_new']));

        }

    }

    if(User::updateUser($user)) {

        //Password Updated
        echo "Password updated";
        die();

    } else {

        //Password Not updated | Server Issues
        echo "Server issues";
        die();
    }

?>