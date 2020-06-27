<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    require '../../vendor/autoload.php';

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['article_id']) || !isset($_POST['reviewer_id'])) {
        echo "Wrong data given";
        die();
    }

    $article_id = $_POST['article_id'];
    $reviewer_id = $_POST['reviewer_id'];

    if(Article::findBy($article_id) == null || User::findBy($reviewer_id) == null) {

        //Article or reviewer doeasnt exist
        echo "Article or reviewer not existing";
        die();      

    }

    //Everything is okey
    if(ArticleToReview::addArticleToReview($article_id, $reviewer_id)) {

        $article = Article::findBy($article_id);
        $article->setStatus('IR');
        Article::updateArticle($article, '../../uploads/');

        //Article to review added
        echo "Article sent to reviewer";
        die();

    } else {

        //Server issues
        echo "Server issues";
        die();

    }

?>