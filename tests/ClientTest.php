<?php
namespace UnitTests;

use BluaBlue\Article;
use BluaBlue\Client;
use BluaBlue\Image;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;

class ClientTest extends TestCase
{
    public function testAuthenticate(){
        $mock = $this->registerResponses([]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $this->assertIsString($bluaBlue->getToken());
    }
    public function testGetArticlesByKeywords()
    {
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],json_encode([$this->getMockArticle()]))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $articles = $bluaBlue->getArticlesByKeywords('anything');
        $this->assertObjectHasAttribute('id',$articles[0]);
    }

    public function testGetOwnArticles()
    {
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],json_encode([$this->getMockArticle()]))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $articles = $bluaBlue->getOwnArticles();
        $this->assertObjectHasAttribute('id',$articles[0]);
    }

    public function testCreateArticles()
    {
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],json_encode([$this->getMockArticle()]))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $article = new Article($this->getMockArticle());
        $created = $bluaBlue->createArticle($article);
        $this->assertObjectHasAttribute('id',$created);
    }

    public function testGetAndUpdateArticles()
    {
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],json_encode([$this->getMockArticle()])),
            new Response(200,['Content-Type'=>'application/json'],json_encode([$this->getMockArticle()]))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $article = $bluaBlue->getArticle('anything');
        $this->assertObjectHasAttribute('id',$article);
        $article->setKeywords('php');
        $updated = $bluaBlue->updateArticle($article);
        $this->assertObjectHasAttribute('id',$updated);
    }

    public function testImages()
    {
        $mockImageResult = json_decode(file_get_contents(__DIR__.'/mockImageResult.json'),true);
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],json_encode($mockImageResult[0])),
            new Response(200,['Content-Type'=>'application/json'],file_get_contents(__DIR__.'/mockImageResult.json'))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $newImage = $bluaBlue->registerImage('https://some.com/1.png');
        $this->assertObjectHasAttribute('id',$newImage);

        $images = $bluaBlue->getImages();
        $this->assertObjectHasAttribute('id',$images[0]);
    }
    public function testGetCategories()
    {
        $mock = $this->registerResponses([
            new Response(200,['Content-Type'=>'application/json'],file_get_contents(__DIR__.'/mockCategoryResult.json'))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
        $bluaBlue->authenticate();
        $categories = $bluaBlue->getCategories();
        $this->assertObjectHasAttribute('id',$categories[0]);
    }

    public function testSetToken()
    {
        $bluaBlue = new Client('some','this','https://blua.blue','v1');
        $bluaBlue->setToken('ABC');

        $this->assertSame('ABC',$bluaBlue->getToken());
    }
    public function testFailedAuth()
    {
        $mock = new MockHandler([
            new RequestException('unauthorized', new Request('POST', 'auth'))
        ]);
        $bluaBlue = new Client('some','this','https://blua.blue','v1', $mock);
//        $this->expectException(RequestException::class);
        $this->expectException(\Exception::class);
        $bluaBlue->authenticate();
    }

    private function getMockArticle()
    {
        return json_decode(file_get_contents(__DIR__ .'/mockArticle.json'),true);
    }
    private function registerResponses($array)
    {
        $array = [new Response(200,['Content-Type'=>'application/json'],json_encode(['token'=>'asdf'])), ...$array];
        return new MockHandler($array);
    }


}
