<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(isset($_GET['editor'])) {
        $id = $_GET['editor'];
    } else if(isset($_GET['user'])) {
        $id = $_GET['user'];
    } else {
        die();
    }

    $target = User::findBy($id);

    if($target != null) {

        if(isset($_GET['editor'])) {

            if(strtolower(get_class($target)) != 'editor') die();

            //Editor Stats
            echo json_encode([
                'Data' => [
                    'new' => count($target->getWaitingArticles()),
                    'sent' => count($target->getInReviewerArticles()),
                    'reviewed' => count($target->getArticleReviewed()),
                    'accepted' => count($target->getAcceptedArticles())
                ],
                'Developer' => 'MJR',
                'Version' => '1.0'
            ]);

        } else if(isset($_GET['user'])) {

            if(strtolower(get_class($target)) == 'editor') die();

            $author = new Author();
            $author->setId($target->getId());

            $reviewer = new Reviewer();
            $reviewer->setId($target->getId());
              
            //User Stats
            echo json_encode([
                'Data' => [
                    'mine' => count($author->getMyArticles()),
                    'toCorrect' => count($author->getArticleToCorrect()),
                    'toReview' => count($reviewer->getArticleToReview()),
                    'reviewed' => count($reviewer->getArticleReviewed())
                ],
                'Developer' => 'MJR',
                'Version' => '1.0'
            ]);
        }

    } else {
        //Editor not existing
    }

?>