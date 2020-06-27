<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $user_email = isset($_GET['email']) ? $_GET['email'] : die();

    $user = User::findBy($user_email, 'email');

    if($user == null) {
        echo json_encode('Error: user not found!');
        die();
    }

    $data = [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'email' => $user->getEmail(),
        'job' => $user->getJob(),
        'type' => $user->getType(),
        'created_at' => $user->getCreatedAt()
    ];

    $result = [
        'Data' => $data,
        'Version' => '1.0',
        'Developer' => 'MJR'
    ];

    echo json_encode($result);

?>