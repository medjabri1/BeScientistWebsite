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

    if(isset($_POST['decision_submit'])) {

        if(!isset($_POST['decision_decision'])) {
            
            header('Location: /');
            die();
            
        }
        
        $article_id = $_POST['decision_article'];
        $reviewer_id = $_POST['decision_reviewer'];
        $decision = $_POST['decision_decision'];

        $editor = Editor::findBy($_SESSION['user_id']);

        if($decision == 'A') {


            $editor->acceptArticle($article_id, $reviewer_id);

            //Article accepted
            header('Location: /home.php?'. sha1('article_accepted'));
            die();

        } else if($decision == 'R') {

            $editor->rejectArticle($article_id);

            //Article Rejected
            header('Location: /home.php?'. sha1('article_rejected'));
            die();

        } else if($decision == 'C') {

            $editor->sendToCorrect($article_id, $reviewer_id);

            //Article need correction
            header('Location: /home.php?'. sha1('for_correction'));
            die();
            
        } else {
            
            //Default value
            header('Location: /');
            die();
            
        }

        //Default value
        header('Location: /');
        die();
        
    } else {

        header('Location: /');
        die();

    }

?>