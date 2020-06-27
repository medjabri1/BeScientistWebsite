<?php

    class Editor extends User {

        //CONSTRUCTORS

        public function __construct()
        {
            parent::__construct();
            $this->setType('E');
        }

        //METHODS

        //Receive All Articles
        public function getAllArticles() { return Article::findAll(); }

        //Receive Accepted Articles
        public function getAcceptedArticles() { return ArticleAccepted::findAll(); }

        //Receive Corrected Articles
        public function getCorrectedArticles() {

            $allArticles = Article::findAll();
            $correctedArticles = array();

            foreach($allArticles as $article) {
                if(strtoupper($article->getStatus()) == 'C') {
                    array_push($correctedArticles, $article);
                }
            }

            return $correctedArticles;

        }

        //Receive Waiting Articles
        public function getWaitingArticles() {

            $allArticles = Article::findAll();
            $waitingArticles = array();

            if($allArticles == null) return $waitingArticles;

            foreach($allArticles as $article) {
                if(strtoupper($article->getStatus()) == 'W' || strtoupper($article->getStatus()) == 'C') {
                    array_push($waitingArticles, $article);
                }
            }

            return $waitingArticles;

        }

        //Receive Articles in Reviewer
        public function getInReviewerArticles() {

            $allArticles = Article::findAll();
            $inReviewerArticles = array();

            if($allArticles == null) return $inReviewerArticles;

            foreach($allArticles as $article) {
                if(strtoupper($article->getStatus()) == 'IR') {
                    array_push($inReviewerArticles, $article);
                }
            }

            return $inReviewerArticles;

        }

        //Get Reviewed Articles
        public function getArticleReviewed() {

            $allArticles = ArticleReviewed::findAll();
            $reviewedArticles = array();

            if($allArticles == null) return $reviewedArticles;

            foreach($allArticles as $article) {

                $artc = Article::findBy($article->getId());
                $status = strtoupper($artc->getStatus());

                if($status == 'RV') {
                    array_push($reviewedArticles, $article);
                }
            }

            return $reviewedArticles;

        }
        //Get Accepted Articles
        public function getArticleAccepted() {

            $allArticles = Article::findAll();
            $acceptedArticles = array();

            if($allArticles == null) return $acceptedArticles;

            foreach($allArticles as $article) {
              if(strtoupper($article->getStatus()) == 'A'){
                  array_push($acceptedArticles, $article);
              }
            }

            return $acceptedArticles;

        }

        //Send Article to review
        // public function sendToReviewer($article_id, $reviewer_id) : bool {

        //     return ArticleToReview::addArticleToReview($article_id, $reviewer_id);

        // }

        //Accept Article
        public function acceptArticle($article_id, $reviewer_id, $path = '../uploads/') : bool {

            $article = Article::findBy($article_id);
            $article->setStatus('A');
            Article::updateArticle($article, $path);

            return ArticleAccepted::addArticleAccepted($article_id, $reviewer_id);

        }

        //Reject Article
        public function rejectArticle($article_id, $path = '../uploads/') : bool {

            $article = Article::findBy($article_id);

            $article->setStatus('R');
            return Article::updateArticle($article, $path);

        }

        //Send Back article for correction
        public function sendToCorrect($article_id, $reviewer_id, $path = '../uploads/') : bool {

            $article = Article::findBy($article_id);
            $article->setStatus('T');
            Article::updateArticle($article, $path);

            return ArticleToCorrect::AddToCorrectArticle($article_id, $reviewer_id);

        }

    }

?>
