<?php

    class ArticleReviewed extends Model {

        //ATTRIBUTES
        private $article_reviewed_id;
        private $reviewer_id;
        private $observation;
        private $created_at;

        public function __construct() {
            $this->article_reviewed_id = 0;
            $this->reviewer_id = 0;
            $this->observation = '';
            $this->created_at = date('d-m-Y H:i:s');
        }

        //GETTERS
        public function getId() { return $this->article_reviewed_id; }
        public function getReviewerId() { return $this->reviewer_id; }
        public function getObservation() { return $this->observation; }
        public function getCreatedAt() { return $this->created_at; }

        //SETTERS 
        public function setId($id) { $this->article_reviewed_id = $id; }
        public function setReviewerId($id) { $this->reviewer_id = $id; }
        public function setObservation($observation) { $this->observation = $observation; }
        public function setCreatedAt($created_at) { $this->created_at = $created_at; }

        //METHODS

        //Get All Articles Reviewed
        public static function findAll($table_name = 'articlereviewed') {

            $data = Model::findAll($table_name);

            if($data == null) return null;

            $articleReviewed = array();

            foreach($data as $info) {

                $article = new ArticleReviewed();
                $article->setId($info['article_reviewed_id']);
                $article->setReviewerId($info['reviewer_id']);
                $article->setObservation($info['observation']);
                $article->setCreatedAt($info['created_at']);

                array_push($articleReviewed, $article);
            }

            $returnedArticles = array();

            for($i = count($articleReviewed) - 1 ; $i >= 0 ; $i--) {

                $article = $articleReviewed[$i];
                $cpt = 0;

                foreach($articleReviewed as $artc) {

                    if($artc->getId() == $article->getId()) {
                        $cpt++;
                    }

                }

                if($cpt > 1) {
                    unset($articleReviewed[$i]);
                }

            } 

            return $articleReviewed;
        }

        //Get One Article
        public static function findBy($value, $column = 'article_reviewed_id', $table_name = 'articlereviewed') {

            $info = Model::findBy($value, $column, $table_name);

            if($info == null) return null;

            $article = new ArticleReviewed();
            $article->setId($info['article_reviewed_id']);
            $article->setReviewerId($info['reviewer_id']);
            $article->setObservation($info['observation']);
            $article->setCreatedAt($info['created_at']);

            return $article;
        }

        //Add Article Reviewed
        public static function addArticleReviewed($article_id, $reviewer_id, $observation, $path = '../uploads/') : bool {

            $article = Article::findBy($article_id);
            $article->setStatus('RV');

            return Model::submitData(
                'insert into articlereviewed(article_reviewed_id, reviewer_id, observation) values (?, ?, ?)',
                [
                    $article_id,
                    $reviewer_id,
                    $observation
                ]
            ) && Model::submitData(
                'update articletoreview set reviewed = 1 where article_toreview_id = ?',
                [
                    $article_id
                ]
            ) && Article::updateArticle($article, $path);
            
        }

    }

?>