<?php

use BluaBlue\Client;

require_once '../vendor/autoload.php';

$sdk = new Client('C45C999F3B5111EC9509D83BBF2ADDD8', '38c52c00ff9b781847aac1a1d9e4101d8088','http://localhost:8080');
$sdk->authenticate();
if($sdk->getToken()){
    /*$article = $sdk->getArticle('second-article');
    file_put_contents(dirname(__DIR__) . '/tests/mockArticle.json', json_encode($article->toArray()));
    file_put_contents(dirname(__DIR__) . '/tests/mockImageResult.json', json_encode($sdk->getImages()));
    file_put_contents(dirname(__DIR__) . '/tests/mockCategoryResult.json', json_encode($sdk->getCategories()));
    $newArticle = new \BluaBlue\Article();
    $newArticle->setName('My awesome article');
    $newArticle->setTeaser('What you always wanted to know about me');
    $newArticle->setCategoryId('F7A3D7DFA54C11EB9242D83BBF2ADDD8');
    $newArticle->setKeywords('biography');*/
    $answer =$sdk->getCategories();
    //
} else {
    $answer = ['auth did not work'];
}
var_dump($answer);

die();