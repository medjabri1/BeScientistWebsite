<?php

    class ArticleToCorrect extends Model {

        //ATTRIBUTES
        private $article_tocorrect_id;
        private $reviewer_id;
        private $corrected;
        private $created_at;

        //CONSTRUCTOR
        public function __construct() {
            $this->article_tocorrect_id = 0;
            $this->reviewer_id = 0;
            $this->corrected = false;
            $this->created_at = date('d-m-Y H:i:s');
        }

        //GETTERS
        public function getId() { return $this->article_tocorrect_id; }
        public function getReviewerId() { return $this->reviewer_id; }
        public function getCorrected() { return $this->corrected; }
        public function getCreatedAt() { return $this->created_at; }

        //SETTERS
        public function setId($id) { $this->article_tocorrect_id = $id; }
        public function setReviewerId($reviewer_id) { $this->reviewer_id = $reviewer_id; }
        public function setCorrected($corrected) { $this->corrected = $corrected; }
        public function setCreatedAt($created_at) { $this->created_at = $created_at; }

        //METHODS

        //Create new Article to correct
        public static function AddToCorrectArticle($article_id, $reviewer_id): bool {
            
            $query = 'insert into articletocorrect (article_tocorrect_id, reviewer_id) values (?, ?)';
            $params = [
                $article_id,
                $reviewer_id
            ];

            return Model::submitData($query, $params);
        }

        //Get all article that should be corrected
        public static function findAll($table_name = 'articletocorrect') {

            $allArticles = Model::findAll($table_name);

            $toCorrectArticles = array();

            if($allArticles == null) return $toCorrectArticles;

            foreach($allArticles as $info) {

                $article = new ArticleToCorrect();

                $article->setId($info['article_tocorrect_id']);
                $article->setReviewerId($info['reviewer_id']);
                $article->setCorrected($info['corrected']);
                $article->setCreatedAt($info['created_at']);

                array_push($toCorrectArticles, $article);

            }
            

            return $toCorrectArticles;
        }

    }

?>