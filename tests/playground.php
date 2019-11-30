<?php

require_once '../vendor/autoload.php';

$sdk = new \BluaBlue\Client('demo', 'sampleUser1');

$answer = $sdk->getArticle('when-politics-kill-innovation');

var_dump($answer);
die();