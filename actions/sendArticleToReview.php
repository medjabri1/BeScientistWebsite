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

    if(!User::findBy($_SESSION['user_id'])->getType() == 'E') {
        header('Location: /');
        die();
    }

    if(isset($_POST['send_submit'])) {

        $article_id = $_POST['send_article'];
        $reviewer_id = $_POST['send_reviewer'];

        if(Article::findBy($article_id) == null || Reviewer::findBy($reviewer_id) == null) {

            //Article or reviewer doeasnt exist
            header('Location: /');
            die();      

        }

        //Everything is okey
        if(ArticleToReview::addArticleToReview($article_id, $reviewer_id)) {

            $article = Article::findBy($article_id);
            $article->setStatus('IR');
            Article::updateArticle($article);

            //Article to review added
            header('Location: /home.php?'. sha1('sended_toreviewer'));
            die();

        } else {

            //Server issues
            header('Location: /home.php?'. sha1('exc_problem'));
            die();

        }

    } else {

        //Post method not submitted
        header('Location: /');
        die();        

    }

?>