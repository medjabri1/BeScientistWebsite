<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    $author_id = isset($_GET['author']) ? $_GET['author'] : die();

    $author = Author::findBy($author_id);

    if($author == null) {
        echo json_encode('Error: author not found!');
        die();
    }

    $art = $author->getMyArticles();

    $articles = array();

    foreach($art as $a) {

        array_push($articles, [
            'id' => $a->getId(),
            'title' => $a->getTitle(),
            'content' => $a->getContent(),
            'author_id' => $a->getAuthorId(),
            'domain' => $a->getDomain(),
            'status' => $a->getStatus(),
            'created_at' => $a->getCreatedAt()
        ]);

    }

    $result = [
        'Data' => $articles,
        'Version' => '1.0',
        'Developer' => 'MJR'
    ];

    echo json_encode($result);

?>