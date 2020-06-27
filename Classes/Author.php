<?php

    class Author extends User {

        //CONSTRUCTORS

        public function __construct()
        {
            parent::__construct();
            $this->setType('A');
        }

        //METHODS

        //Create Article
        public function createArticle(Article $article, $path = "../uploads/") : bool {

            $article->setAuthorId($this->id); 
            $add = Article::addArticle($article);

            $myArticles = $this->getMyArticles();
            $article = $myArticles[0];
            $article->createPDF($path);

            return $add;

        }

        //Correct Article
        public function correctArticle(Article $old_article,Article $new_article) : bool {

            $old_article->setTitle($new_article->getTitle());
            $old_article->setContent($new_article->getContent());
            $old_article->setDomain($new_article->getDomain());
            $old_article->setStatus('C');

            return Article::updateArticle($old_article);

        }

        //Get All Articles that belongs to this author
        public function getMyArticles() {

            $allArticles = Article::findAll();

            $myArticles = array();

            if($allArticles == null) return $myArticles;

            for($i = 0; $i < count($allArticles); $i++) {

                $article = $allArticles[$i];

                if($article->getAuthorId() == $this->id) {

                    array_push($myArticles, $article);

                }

            }

            return $myArticles;

        }

        //Get all articles to correct
        public function getArticleToCorrect() {
            
            $allArticles = ArticleToCorrect::findAll();

            $myArticles = array();

            if($allArticles == null) return $myArticles;

            foreach($allArticles as $article) {

                if(Article::findBy($article->getId())->getAuthorId() == $this->getId() && strtoupper(Article::findBy($article->getId())->getStatus()) == 'T' && $article->getCorrected() == false) {

                    array_push($myArticles, $article);

                }

            }

            return $myArticles;
        }

    }

?>