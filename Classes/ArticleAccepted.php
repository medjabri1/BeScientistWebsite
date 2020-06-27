<?php

    class ArticleAccepted extends Model {

        //ATTRIBUTES
        private $article_accepted_id;
        private $reviewer_id;
        private $created_at;

        //CONSTRUCTOR
        public function __construct() {
            $this->article_accepted_id = 0;
            $this->reviewer_id = 0;
            $this->created_at = date('d-m-Y H:i:s');
        }

        //GETTERS
        public function getId() { return $this->article_accepted_id; }
        public function getReviewerId() { return $this->reviewer_id; }
        public function getCreatedAT() { return $this->created_at; }

        //SETTERS
        public function setId($id) { $this->article_accepted_id = $id; }
        public function setReviewerId($reviewer_id) { $this->reviewer_id = $reviewer_id; }
        public function setCreatedAt($created_at) { $this->created_at = $created_at; }

        //METHODS

        //Get All accepted articles
        public static function findAll($table_name = 'articleaccepted')
        {
            $data = Model::findAll($table_name);

            $acceptedArticles = array();

            if($data == null) return $acceptedArticles;

            foreach($data as $info) {

                $article = new ArticleAccepted();

                $article->setId($info['article_accepted_id']);
                $article->setReviewerId($info['reviewer_id']);
                $article->setCreatedAt($info['created_at']);

                array_push($acceptedArticles, $article);

            }

            return $acceptedArticles;
        }
        
        //Accept article
        public static function addArticleAccepted($article_id, $reviewer_id) : bool {

            return Model::submitData(
                'insert into articleaccepted (article_accepted_id, reviewer_id) values (?, ?)',
                [
                    $article_id,
                    $reviewer_id
                ]
            );

        }

    }

?>