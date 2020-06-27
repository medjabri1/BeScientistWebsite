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

    if(isset($_POST['review_submit'])) {

        $article_id = $_POST['review_article'];
        $reviewer_id = $_SESSION['user_id'];
        $observation = $_POST['review_observation'];

        if(Article::findBy($article_id) == null) {

            //Article doesnt exist
            header('Location: /');
            die();

        } 

        if(ArticleToReview::findBy($article_id) == null) {

            //Article not sent to review
            header('Location: /');
            die();

        }

        if(ArticleReviewed::addArticleReviewed($article_id, $reviewer_id, $observation)) {

            //Article reviewed successfully
            header('Location: /home.php?'. sha1('reviewed_success'));
            die();

        } else {

            //Server issues
            header('Location: /home.php?'. sha1('exc_problem'));
            die();

        }

    } else {

        header('Location: /');
        die();

    }

?>