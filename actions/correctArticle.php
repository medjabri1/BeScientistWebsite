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

    if(isset($_POST['correct_submit'])) {

        $article_id = $_POST['correct_article'];
        $title = $_POST['correct_title'];
        $content = $_POST['correct_content'];

        if(($article = Article::findBy($article_id)) == null) {

            header('Location: /');
            die();

        } 

        $article->setTitle($title);
        $article->setContent($content);
        $article->setStatus('C');

        if(Article::updateArticle($article)) {

            Model::submitData(
                'update articletocorrect set corrected = 1 where article_tocorrect_id = ?',
                [
                    $article_id
                ]
            );
            
            //Article corrected successffully
            header('Location: /home.php?'.sha1('article_corrected'));
            die();
            
        } else {
            
            header('Location: /home.php?'.sha1('exc_problem'));
            die();

        }

    } else {

        header('Location: /');
        die();

    }

?>