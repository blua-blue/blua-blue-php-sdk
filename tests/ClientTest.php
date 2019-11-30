<?php

namespace BluaBlue;

use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public static $instance;


    public static function setUpBeforeClass():void
    {
        self::$instance = new Client('demo', 'sampleUser1');
    }

    public function testGetArticleBySlug()
    {
        $slug = 'sample-article';
        $request = self::$instance->getArticle($slug);
        $this->assertArrayHasKey('id',$request);
    }
    public function testGetArticleById(){
        $id = 'B6063A13132511EA840C0AA5E628989E';
        $request = self::$instance->getArticle($id);
        $this->assertArrayHasKey('id',$request);
    }

    public function testGetArticleList()
    {
        $this->assertIsArray(self::$instance->getArticleList());
    }
}
