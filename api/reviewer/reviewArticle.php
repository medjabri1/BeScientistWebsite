<?php

    require '../../vendor/autoload.php';

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['reviewer_id']) || !isset($_POST['article_id'])) {
        json_encode('Rani hna!');
        die();
    }

    $reviewer_id = $_POST['reviewer_id'];
    $article_id = $_POST['article_id'];

    if(!User::findBy($reviewer_id)) {
        //Reviewed not found
        json_encode('Error: reviewer not found!');
        die();
    }

    if(!Article::findBy($article_id)) {
        //Article not found
        json_encode('Error: Article not found!');
        die();
    }

    if(ArticleToReview::findBy($article_id) == null) {
        //Article not sent to review
        json_encode('Error: article not sent to review!');
        die();
    }

    $observation = $_POST['observation'];

    if(ArticleReviewed::addArticleReviewed($article_id, $reviewer_id, $observation, '../../uploads/')) {
        //Article reviewed
        json_encode('Article reviewed!');
        die();
    } else {
        //Article not reviewed
        json_encode('Article not reviewed!');
        die();
    }

?>