<?php

    header('Access-Control-Allow-Origin: *');
    header('content-type:application/json');

    require '../../vendor/autoload.php';

    spl_autoload_register(function ($class_name) {
        include '../../Classes/'. $class_name . '.php';
    });

    if(!isset($_POST['article_id'])) die();

    $article_id = $_POST['article_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $article = Article::findBy($article_id);

    if($article == null) {
        echo json_encode('Error: Article not found!');
        die();
    }

    $article->setTitle($title);
    $article->setContent($content);
    $article->setStatus('C');

    if(Article::updateArticle($article, '../../uploads/')) {

        Model::submitData(
            'update articletocorrect set corrected = 1 where article_tocorrect_id = ?',
            [
                $article_id
            ]
        );

        echo json_encode('Article corrected!');
        die();
    } else {
        echo json_encode('Article not corrected!');
        die();
    }

?>