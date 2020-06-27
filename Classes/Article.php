<?php

    use  Dompdf\Dompdf;

    class Article extends Model {

        //VARIABLES

        private static $statuses = [
            'A' => 'Accepted',
            'Aj' => 'Ajouter dans le volume',
            'R' => 'Rejected',
            'C' => 'Corrected',
            'T' => 'To Correct',
            'W' => 'Waiting',
            'RV' => 'Reviewed',
            'IR' => 'Reviewing..'
        ];

        /////////////////////////////////////////////////////////////////////////////////////
        //ATTRIBUTES

        protected $id;
        protected $author_id;
        protected $title;
        protected $content;
        protected $domain;
        protected $status;
        protected $created_at;

        /////////////////////////////////////////////////////////////////////////////////////
        //CONSTRUCTORS

        public function __construct()
        {
            $this->id = 0;
            $this->author_id = 0;
            $this->title = '';
            $this->content = '';
            $this->domain = '';
            $this->status = 'W';
            $this->created_at = date('d-m-Y H:i:s');
        }

        /////////////////////////////////////////////////////////////////////////////////////
        //SETTERS

        public function setId($id) : void { $this->id = $id; }
        public function setAuthorId($author_id) : void { $this->author_id = $author_id; }
        public function setTitle($title) : void { $this->title = $title; }
        public function setContent($content) : void { $this->content = $content; }
        public function setDomain($domain) : void { $this->domain = $domain; }
        public function setStatus($status) : void { $this->status = $status; }
        public function setCreatedAt($created_at) : void { $this->created_at = $created_at; }

        /////////////////////////////////////////////////////////////////////////////////////
        //GETTERS

        public function getId() : int { return $this->id; }
        public function getAuthorId() : int { return $this->author_id; }
        public function getTitle() : string { return $this->title; }
        public function getContent() : string { return $this->content; }
        public function getDomain() : string { return $this->domain; }
        public function getStatus() : string { return $this->status; }
        public function getRealStatus() : string { return self::$statuses[$this->status]; }
        public function getCreatedAt() { return $this->created_at; }

        /////////////////////////////////////////////////////////////////////////////////////
        //METHODS

        //Get Article By Id
        public static function findBy($value, $column = 'article_id', $table_name = 'article') {

            $data = parent::findBy($value, $column, $table_name);

            if($data != null) {

                $article = new Article();

                $article->setId($data['article_id']);
                $article->setAuthorId($data['author_id']);
                $article->setTitle($data['title']);
                $article->setContent($data['content']);
                $article->setDomain($data['domain']);
                $article->setStatus($data['state']);
                $article->setCreatedAt($data['created_at']);

                return $article;

            } else {
                return null;
            }

        }

        //Get All articles
        public static function findAll($table_name = 'article') {

            $data = parent::findAll($table_name);

            if($data != null) {

                $articles = array(count($data));

                for($i = 0; $i < count($data); $i++) {

                    $info = $data[$i];

                    $article = new Article();

                    $article->setId($info['article_id']);
                    $article->setAuthorId($info['author_id']);
                    $article->setTitle($info['title']);
                    $article->setContent($info['content']);
                    $article->setDomain($info['domain']);
                    $article->setStatus($info['state']);
                    $article->setCreatedAt($info['created_at']);

                    $articles[$i] = $article;
                }

                return $articles;

            } else {
                return null;
            }

        }

        //Add new Article
        public static function addArticle($article) : bool {

            $query = 'insert into article (author_id, title, content, domain, state) values (?, ?, ?, ?, ?)';
            $params = [
                $article->getAuthorId(),
                $article->getTitle(),
                $article->getContent(),
                $article->getDomain(),
                $article->getStatus()
            ];

            $addArticle = parent::submitData($query, $params);

            return $addArticle;
        }

        //Update Article
        public static function updateArticle($article, $path = '../uploads/') : bool {

            $query = 'update article set title = ?, content = ?, domain = ?, state = ? where article_id = ?';
            $params = [
                $article->getTitle(),
                $article->getContent(),
                $article->getDomain(),
                $article->getStatus(),
                $article->getId()
            ];

            $article->createPDF($path);
            return parent::submitData($query, $params);
        }

        //Delete Article
        public static function deleteArticle($article) : bool {

            $deleteObject = true;

            switch(strtoupper($article->getStatus())) {

                case 'A':
                    $deleteObject = parent::submitData('delete from articleaccepted where article_accepted_id = ?', [ $article->getId() ]);
                    break;

                case 'T':
                    $deleteObject = parent::submitData('delete from articleaccepted where article_tocorrect_id = ?', [ $article->getId() ]);
                    break;

                case 'RV':
                    $deleteObject = parent::submitData('delete from articletoreview where article_toreview_id = ?', [ $article->getId() ]);
                    break;
            }

            $deleteArticle = parent::submitData('delete from article where article_id = ?', [ $article->getId() ]);

            return $deleteObject && $deleteArticle;

        }

        public function createPDF($path = '../uploads/') : bool {

            $title = $this->getTitle();
            $domain = $this->getDomain();
            $author = Author::findBy($this->getAuthorId())->getName();
            $content = $this->getContent();
            $id = $this->getId();

            $document = new Dompdf();

            $page = "<!DOCTYPE html><html lang='en'><head>
                    <meta charset='UTF-8'><title>$title</title><style>
                    *, *::after, *::before {padding: 0;
                    margin: 0;
                    box-sizing: border-box;color: #F1F1F1;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-weight: normal;}body {
                    width: 100%;
                    height: 200vh;background-color: #121212;min-height: fit-content;display: flex;flex-direction: column;align-items: center;justify-content: flex-start;padding: 100px;}.title {
                    word-spacing: 2px;letter-spacing: 1px;font-size: 2em;font-weight: 700;text-transform: uppercase;}.domain {
                    font-style: italic;color: #BBB;
                    word-spacing: 3px;}.author {
                    margin: 20px 0;color: #C1C1C1;}.author span {color: #EEE;
                    word-spacing: 2px;letter-spacing: 1px;}.content {
                    word-spacing: 2px;text-align: left;margin: 100px 0;font-size: 1em;}</style>
                    </head>
                    <body>
                    <!-- <div class='container'> --><h2 class='title'>$title</h2><p class='domain'>$domain</p><p class='author'>Written by : <span>$author</span></p><p class='content'>$content
                    </p>
                    <!-- </div> --></body>
                    </html>
            ";

            $document->loadHTML($page);
            $document->setPaper('A4', 'Portrait');

            $document->render();

            $pdf_gen = $document->output();

            // $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

            return file_put_contents($path .''. $id .'.pdf', $pdf_gen);

        }

    }

?>
