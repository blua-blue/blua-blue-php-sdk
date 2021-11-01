<?php


use BluaBlue\Article;
use PHPUnit\Framework\TestCase;

class ArticleTest extends TestCase
{
    function testSettersGetters()
    {
        $article = new Article();
        foreach (['Name','Teaser','ImageId','AuthorUserId','CategoryId','IsPublic','KeyWords','PublishDate','UpdateDate','DeleteDate'] as $i => $property){
            $set = 'set'.$property;
            $get = 'get'.$property;
            $value = $property === 'IsPublic' ? 1 : 'v-'.$i;
            $article->$set($value);
            $this->assertSame($article->$get(), $value);
        }
    }
    function testGettersOnly()
    {
        $mock = [
            'id'=> '123',
            'slug' => 'my-article',
            'insert_date' => '2020'
        ];
        $article = new Article($mock);
        foreach ($mock as $key => $value){
            $get = 'get'. ucfirst(preg_replace_callback('/_([a-z])/',fn($matches) => ucfirst($matches[1]),$key));
            $this->assertSame($article->$get(), $value);
        }
    }
    function testDeep()
    {
        $article = new Article();
        foreach (['ArticleComment','ArticleContent','ArticleRating','ArticleStore'] as $deep){
            $setter = 'add' . $deep;
            $remover = 'remove' . $deep;
            $getter = 'get' . $deep;
            // add & get
            $this->assertIsArray($article->$setter(['id'=>'123'])->$getter());
            // get
            $this->assertSame(count($article->$getter()), 1);
            // remove
            $this->assertSame($article->$remover('123')->$getter()[0]['delete_date'], '.');
        }
    }

    function testGetHmtl()
    {
        $article = new Article();
        $html = '<p>hi</p>';
        $article->addArticleContent(['html'=>$html]);
        $this->assertSame($article->getContentHtml(), $html);

    }

}
