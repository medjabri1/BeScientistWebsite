<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    require '../../vendor/autoload.php';

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['decision']) || !isset($_POST['editor_id']) || !isset($_POST['article_id']) || !isset($_POST['reviewer_id'])) {
        
        echo "Wrong data given";
        die();
        
    }
    
    $editor_id = $_POST['editor_id'];
    $article_id = $_POST['article_id'];
    $reviewer_id = $_POST['reviewer_id'];
    $decision = strtoupper($_POST['decision']);

    $editor = Editor::findBy($editor_id);

    if($editor == null) {

        echo "Editor not found";
        die();

    }

    if(Article::findBy($article_id) == null) {

        echo "Article not found";
        die();

    }

    if(User::findBy($reviewer_id) == null) {

        echo "Reviewer not found";
        die();

    }

    if($decision == 'A') {


        $editor->acceptArticle($article_id, $reviewer_id, '../../uploads/');

        //Article accepted
        echo "Article accepted";
        die();

    } else if($decision == 'R') {

        $editor->rejectArticle($article_id, '../../uploads/');

        //Article Rejected
        echo "Article rejected";
        die();

    } else if($decision == 'C') {

        $editor->sendToCorrect($article_id, $reviewer_id, '../../uploads/');

        //Article need correction
        echo "Article sent for correction";
        die();
        
    } else {
        
        //Default value
        echo "Decision value not recognized";
        die();
        
    }

?>