<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $reviewers_array = Reviewer::findAll();

    if($reviewers_array == null) {
        echo json_encode([
            'Data' => '',
            'Developer' => 'MJR',
            'Version' => '1.0'
        ]);
        die();
    }

    $reviewers = array();

    foreach($reviewers_array as $reviewer) {
        $rev = array();
        
        $reviewerObject = new Reviewer();
        $reviewerObject->setId($reviewer->getId());

        $toreview = $reviewerObject->getArticleToReview();
        
        $rev = [
            'id' => $reviewer->getId(),
            'name' => $reviewer->getName(),
            'email' => $reviewer->getEmail(),
            'toreview' => count($toreview)
        ];
        array_push($reviewers, $rev);
    }

    //Editor Stats
    echo json_encode([
        'Data' => $reviewers,
        'Developer' => 'MJR',
        'Version' => '1.0'
    ]);

?>