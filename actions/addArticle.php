<?php

    require '../vendor/autoload.php';
    
    spl_autoload_register(function ($class_name) {
        include '../Classes/'. $class_name . '.php';
    });

    session_start();

    if(!isset($_SESSION['user_id'])) {
        header('Location: /');
        die();
    }

    if($_POST['add_submit']) {

        $title = $_POST['add_title'];
        $content = $_POST['add_content'];
        $domain = $_POST['add_domain'];

        $article = new Article();

        $article->setTitle($title);
        $article->setContent($content);
        $article->setDomain($domain);

        if(Author::findBy($_SESSION['user_id'])->createArticle($article)) {

            header('Location: /home.php?'. sha1('article_added'));
            die();

        } else {

            header('Location: /home.php?'. sha1('exc_problem'));
            die();

        }

        

    } else {

        header('Location: /');
        die();

    }

?>