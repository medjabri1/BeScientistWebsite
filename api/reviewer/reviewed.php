<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $reviewer_id = isset($_GET['reviewer']) ? $_GET['reviewer'] : die();

    $reviewer = reviewer::findBy($reviewer_id);

    if($reviewer == null) {
        echo json_encode('Error: reviewer not found!');
        die();
    }

    $reviewer = new Reviewer();
    $reviewer->setId($reviewer_id);

    $art = $reviewer->getArticleReviewed();

    $articles = array();

    foreach($art as $ar) {

        $a = Article::findBy($ar->getId());

        array_push($articles, [
            'id' => $a->getId(),
            'title' => $a->getTitle(),
            'content' => $a->getContent(),
            'domain' => $a->getDomain(),
            'observation' => $ar->getObservation(),
            'reviewed_at' => $ar->getCreatedAt()
        ]);

    }

    $result = [
        'Data' => $articles,
        'Version' => '1.0',
        'Developer' => 'MJR'
    ];

    echo json_encode($result);

?>