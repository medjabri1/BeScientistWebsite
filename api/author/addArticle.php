<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    require '../../vendor/autoload.php';

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['author_id'])) die();

    $article = new Article();
    $author = User::findBy($_POST['author_id']);

    if($author == null) {
        echo json_encode('Error: Author not found!');
        die();
    }

    $author = new Author();
    $author->setId($_POST['author_id']);

    $article->setTitle($_POST['title']);
    $article->setContent($_POST['content']);
    $article->setDomain($_POST['domain']);

    if($author->createArticle($article, '../../uploads/')) {
        //Article created;
        echo json_encode('Article created!');
        die();
    } else {
        //Article not created
        echo json_encode('Article not created!');
        die();
    }

?>