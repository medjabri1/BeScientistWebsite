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

            $new_array = $target->getWaitingArticles();
            $sent_array = $target->getInReviewerArticles();
            $reviewed_array = $target->getArticleReviewed();
            $accepted_array = $target->getAcceptedArticles();

            $new = $sent = $reviewed = $accepted = [];

            foreach($new_array as $data) {
                array_push($new, [
                    'id' => $data->getId(),
                    'author_id' => $data->getAuthorId(),
                    'author_name' => User::findBy($data->getAuthorId())->getName(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'created_at' => $data->getCreatedAt()
                ]);
            }

            foreach($sent_array as $data) {
                array_push($sent, [
                    'id' => $data->getId(),
                    'author_id' => (int) $data->getAuthorId(),
                    'author_name' => User::findBy($data->getAuthorId())->getName(),
                    'reviewer_id' => (int) User::findBy(ArticleToReview::findBy($data->getId())->getReviewerId())->getId(),
                    'reviewer_name' => User::findBy(ArticleToReview::findBy($data->getId())->getReviewerId())->getName(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'created_at' => $data->getCreatedAt(),
                    'sent_at' => ArticleToReview::findBy($data->getId())->getCreatedAt()
                ]);
            }

            foreach($reviewed_array as $aReviewed) {
                $data = Article::findBy($aReviewed->getId());

                array_push($reviewed, [
                    'id' => $data->getId(),
                    'author_id' => (int) $data->getAuthorId(),
                    'author_name' => User::findBy($data->getAuthorId())->getName(),
                    'reviewer_id' => (int) (ArticleReviewed::findBy($data->getId()))->getReviewerId(),
                    'reviewer_name' => User::findBy(ArticleReviewed::findBy($data->getId())->getReviewerId())->getName(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'observation' => $aReviewed->getObservation(),
                    'created_at' => $data->getCreatedAt(),
                    'reviewed_at' => ArticleReviewed::findBy($data->getId())->getCreatedAt()
                ]);
            }

            foreach($accepted_array as $aAccepted) {
                $data = Article::findBy($aAccepted->getId());

                array_push($accepted, [
                    'id' => $data->getId(),
                    'author_id' => (int) $data->getAuthorId(),
                    'author_name' => User::findBy($data->getAuthorId())->getName(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'created_at' => $data->getCreatedAt(),
                ]);
            }

            //Editor Stats
            echo json_encode([
                'Data' => [
                    'new' => $new,
                    'sent' => $sent,
                    'reviewed' => $reviewed,
                    'accepted' => $accepted
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

            $mine_array = $author->getMyArticles();
            $toCorrect_array = $author->getArticleToCorrect();
            $toReview_array = $reviewer->getArticleToReview();
            $reviewed_array = $reviewer->getArticleReviewed();
            
            $mine = $toCorrect = $toReview = $reviewed = [];

            foreach($mine_array as $data) {
                array_push($mine, [
                    'id' => $data->getId(),
                    'author_id' => $data->getAuthorId(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'status' => $data->getStatus(),
                    'created_at' => $data->getCreatedAt()
                ]);
            }

            foreach($toCorrect_array as $aToCorrect) {

                $data = Article::findBy($aToCorrect->getId());
                
                array_push($toCorrect, [
                    'id' => $data->getId(),
                    'author_id' => $data->getAuthorId(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'status' => $data->getStatus(),
                    'created_at' => $data->getCreatedAt()
                ]);
            }

            foreach($toReview_array as $aToReview) {

                $data = Article::findBy($aToReview->getId());
                
                array_push($toReview, [
                    'id' => $data->getId(),
                    'author_id' => $data->getAuthorId(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'status' => $data->getStatus(),
                    'created_at' => $data->getCreatedAt(),
                    'sent_at' => $aToReview->getCreatedAt()
                ]);
            }

            foreach($reviewed_array as $aReviewed) {

                $data = Article::findBy($aReviewed->getId());
                
                array_push($reviewed, [
                    'id' => $data->getId(),
                    'author_id' => $data->getAuthorId(),
                    'title' => $data->getTitle(),
                    'content' => $data->getContent(),
                    'domain' => $data->getDomain(),
                    'observation' => $aReviewed->getObservation(),
                    'status' => $data->getStatus(),
                    'created_at' => $data->getCreatedAt(),
                    'reviewed_at' => $aReviewed->getCreatedAt()
                ]);
            }
              
            //User Stats
            echo json_encode([
                'Data' => [
                    'mine' => $mine,
                    'toCorrect' => $toCorrect,
                    'toReview' => $toReview,
                    'reviewed' => $reviewed
                ],
                'Developer' => 'MJR',
                'Version' => '1.0'
            ]);
        }

    } else {
        //Editor not existing
    }

?>