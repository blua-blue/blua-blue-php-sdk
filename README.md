# blua.blue PHP SDK

[![Build Status](https://travis-ci.com/blua-blue/blua-blue-php-sdk.svg?branch=master)](https://travis-ci.com/blua-blue/blua-blue-php-sdk)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5d8e1b32be829ece1e55/test_coverage)](https://codeclimate.com/github/blua-blue/blua-blue-php-sdk/test_coverage)

The goal of this package is to enable fast, secure and simple integration of your blua.blue 
content into own web domains. _NOTE_: this library also works for open-source installations of blua.blue


## Installation

`composer require blua-blue/blua-blue-php-sdk`

## Usage

```PHP
require __DIR__ . 'vendor/autoload.php';

$client = new BluaBlue\Client('yourPublicKey', 'yourAPIkey');
try{
    $client->authenticate();
    $myArticles = $client->getOwnArticles();
    foreach ($myArticles as $article){
        echo $article->getName();
        echo $article->getContentHtml();
    }
} catch (Exception $e) {
 ...
}
```

## Client Methods

### Constructor

`$bluaBlue = new Client($publicKey, $apiKey=null, $apiEndpoint = 'https://blua.blue')`



### getArticle($articleSlugOrID)

This method accepts either the unique ID or the unique article-slug of a particular article.

### getOwnArticles()

Retrieves all owned articles regardless of publish-state

### getArticlesByKeywords($keywords)

Comma-seperated or single keyword
e.g.

`$phpTutorials = getArticlesByKeywords('php,tutorial');`

### getCategories()

Retrieves a list of available categories

### getImages()

Retrieves stored images 

### registerImage($pathOrBase64, $mode='external')

MODES: external | upload

Either registers external images for reference or accepts base64 encoded image strings.
(all browser-native content-types, max 600kb)




### createArticle($article)

Creates a new article. While you can pass in a simple array, we recommend using the wrapper:

```php
$bluaBlue = new Client(getenv('publicKey'), getenv('apiKey'));
$newArticle = new \BluaBlue\Article();
$newArticle->setName('My awesome article');
$newArticle->setTeaser('What you always wanted to know about me');
$newArticle->setCategoryId('F7A3D7DFA54C11EB9242D83BBF2ADDD8');
$newArticle->setKeywords('biography');
$newArticle->addArticleContent([
    'sort'=>1, 
    'content_type'=>'markdown',
    'content'=>'## hi there'
    ])
//...
$bluaBlue->createArticle($newArticle);
```

### updateArticle($article)

Similar to createArticle, but operates on an existing article

```php
$bluaBlue = new Client(getenv('publicKey'), getenv('apiKey'));
$myArticle = $bluaBlue->getArticle('my-awesome-article')
$myArticle->addArticleContent([
    'sort'=>count($myArticle->getArticleContent())+1, 
    'content_type'=>'markdown',
    'content'=>'## chapter 2 ...'
    ]);
//...
$bluaBlue->updateArticle($myArticle);
```

## Article Wrapper

The article wrapper can be constructed with an array:

`new Article(['name'=>'How to set up x'])` or empty
`new Article()`

Either way, the wrapper has setters and getters for the following properties:


- $id (getter only)
- $name
- $slug (getter only)
- $teaser
- $image_id
- $author_user_id (setter will be overwritten by endpoint)
- $category_id 
- $is_public
- $keywords
- $publish_date
- $insert_date (getter only)
- $update_date
- $delete_date
- $article_content
- $article_store

Example:

```php 
$new = new Article();
$new->setName('Title of my article');
echo $new->getName(); // prints 'Title of my article'
```
_NOTE_: you can let the endpoint determine certain values based on neoan3-db logik:

`$article->setDeleteDate('.')` will be translated to the current ("point") in time when the request is received.
In other words, the SQL equivalent _NOW()_ 

## Image Wrapper
The image wrapper can be constructed with an array:

new Image(['format'=>'image/png']) or empty new Image()

Either way, the wrapper has setters and getters for the following properties:

- id (getter only)
- path
- format
- inserterUserId (getter only)

Example:

```php 
$new = new Image();
$new->setPath('https://s3.aws.com/234.asdf');
echo $new->getPath(); // prints 'https://s3.aws.com/234.asdf'
```

## Additional methods on Article and Image

### toArray()
Exports object into assoc-array.

## Additional method on Article

### getContentHtml()
Exports a combined HTML-string from all contents

```php 
$article = $blueAblue->getArticle('best-article-I-wrote');
$allContent = $article->getContentHtml();
// equivalent to:
$allContent = '';
foreach($article->getArticleContent() as $content){
    $allContent .= $content['html'];
}
```