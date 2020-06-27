<?php

    class Reviewer extends User {

        //CONSTRUCTORS

        public function __construct()
        {
            parent::__construct();
            $this->setType('R');
        }

        //METHODS
        public static function findAll($table_name = 'reviewer') {
            
            $users = User::findAll();

            if($users != null) {

                $reviewers = array();

                for($i = 0; $i < count($users); $i++) {

                    $user = $users[$i];

                    if($user->getType() == 'A' || $user->getType() == 'R') {
                        array_push($reviewers, $user);
                    }
                }

                return $reviewers;

            } else {
                return null;
            }
        }

        //Receive Articles to review
        public function getArticleToReview() {

            $allArticles = ArticleToReview::findAll();
            
            $myArticles = array();

            foreach($allArticles as $article) {
                if($article->getReviewerId() == $this->getId() && $article->getReviewed() == false) {
                    array_push($myArticles, $article);
                }
            }

            return $myArticles;

        }

        //Get Reviewed Article
        public function getArticleReviewed() {

            $allArticles = ArticleReviewed::findAll();
            
            $myArticles = array();

            foreach($allArticles as $article) {
                if($article->getReviewerId() == $this->getId()) {
                    array_push($myArticles, $article);
                }
            }

            return $myArticles;

        }

    }

?>