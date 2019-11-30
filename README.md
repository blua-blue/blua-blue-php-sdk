# blua.blue PHP SDK

[![Build Status](https://travis-ci.com/blua-blue/blua-blue-php-sdk.svg?branch=master)](https://travis-ci.com/blua-blue/blua-blue-php-sdk)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5d8e1b32be829ece1e55/test_coverage)](https://codeclimate.com/github/blua-blue/blua-blue-php-sdk/test_coverage)

## Installation

`composer require blua-blue/blua-blue-php-sdk`

## Usage

```PHP
require __DIR__ . 'vendor/autoload.php';

$client = new BluaBlue\Client('userName', 'Password', 'https://my-blua-blue-installation.com/api.v1/');

$allArticles = $client->getArticleList();

```

## Methods

### Constructor

`new Client($userName, $password, [$apiEndpoint])`

The API endpoint defaults to https://blua.blue/api.v1/

### getArticle($articleSlugOrID)

This method accepts either the unique ID or the unique article-slug of a particular article.

### getArticleList([$offset = 0, $limit = 100, $author = null])

Without any properties, this method retrieves up to 300 articles. 

If authenticated user is admin and no author is set:
Any article

If authenticated user is not admin and no author is set:
Any published article

If authenticated user equals $author OR authenticated user is admin:
All articles of author

If authenticated user is not admin: All articles of given author with the status published

_NOTE_: at time of publishing offset & limit are not yet implemented in the official blua.blue repository