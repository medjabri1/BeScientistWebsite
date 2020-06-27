<?php

    class ArticleToReview extends Model {

        //ATTRIBUTES
        private $article_toreview_id;
        private $reviewer_id;
        private $reviewed;
        private $created_at;

        public function __construct() {
            $this->article_toreview_id = 0;
            $this->review_id = 0;
            $this->reviewed = false;
            $this->created_at = date('d-m-Y H:i:s');
        }

        //GETTERS
        public function getId() { return $this->article_toreview_id; }
        public function getReviewerId() { return $this->reviewer_id; }
        public function getReviewed() { return $this->reviewed; }
        public function getCreatedAt() { return $this->created_at; }

        //SETTERS 
        public function setId($id) { $this->article_toreview_id = $id; }
        public function setReviewerId($id) { $this->reviewer_id = $id; }
        public function setReviewed($reviewed) { $this->reviewed = $reviewed; }
        public function setCreatedAt($created_at) { $this->created_at = $created_at; }

        //METHODS

        //Get All Articles To Review
        public static function findAll($table_name = 'articletoreview') {

            $data = Model::findAll($table_name);

            if($data == null) return null;

            $articleToReview = array();

            foreach($data as $info) {

                $article = new ArticleToReview();
                $article->setId($info['article_toreview_id']);
                $article->setReviewerId($info['reviewer_id']);
                $article->setReviewed($info['reviewed']);
                $article->setCreatedAt($info['created_at']);

                array_push($articleToReview, $article);
            }

            return $articleToReview;
        }

        //Get One Article
        public static function findBy($value, $column = 'article_toreview_id', $table_name = 'articletoreview') {

            $info = Model::findBy($value, $column, $table_name);

            if($info == null) return null;

            $article = new ArticleToReview();
            $article->setId($info['article_toreview_id']);
            $article->setReviewerId($info['reviewer_id']);
            $article->setReviewed($info['reviewed']);
            $article->setCreatedAt($info['created_at']);

            return $article;
        }

        //Add Article To Review
        public static function addArticleToReview($article_id, $reviewer_id) : bool {

            return Model::submitData(
                'insert into articletoreview(article_toreview_id, reviewer_id) values (?, ?)',
                [
                    $article_id,
                    $reviewer_id
                ]
            );
            
        }

    }

?>