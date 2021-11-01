<?php


namespace BluaBlue;


use Exception;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\HandlerStack;

/**
 * Class Client
 * @package BluaBlue
 */
class Client
{

    /**
     * @var
     */
    private $publicKey;
    /**
     * @var
     */
    private $privateKey;
    /**
     * @var \GuzzleHttp\Client
     */
    private $guzzleClient;
    /**
     * @var
     */
    private $token;
    /**
     * @var
     */
    private $currentUser;

    /**
     * Client constructor.
     *
     * @param $publicKey
     * @param null $privateKey
     * @param string $baseUrl
     * @param string $version
     * @throws Exception
     */
    function __construct($publicKey, $privateKey = null, string $baseUrl = 'https://blua.blue', string $version = 'v1', $mockHandler = false)
    {
        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;
        $guzzleConfig = [
            'base_uri' => $baseUrl . '/api.' . $version . '/',
            'timeout' => 2.0,
            'headers' => ['Content-Type' => 'application/json']
        ];
        if($mockHandler){
            $guzzleConfig['handler'] = HandlerStack::create($mockHandler);
        }
        $this->guzzleClient = new \GuzzleHttp\Client($guzzleConfig);
    }


    /**
     * @throws Exception
     */
    function getArticlesByKeywords(string $keywords):array
    {
        return $this->articleResults($this->getWrapper('article/keyword/'.$keywords));
    }

    /**
     * @throws Exception
     */
    function getOwnArticles(): array
    {
        return $this->articleResults($this->getWrapper('article/mine'));
    }

    /**
     * @param $article
     * @return Article
     * @throws Exception
     */
    function createArticle($article): Article
    {
        if($article instanceof Article){
            $article = $article->toArray();
        }
        return new Article($this->hasBodyWrapper('article', $article));
    }

    /**
     * @param $article
     * @return Article
     * @throws Exception
     */
    function updateArticle($article): Article
    {
        return new Article($this->hasBodyWrapper('article', $article, 'PUT'));
    }

    /**
     * @param $articleIdOrSlug
     *
     * @return Article
     * @throws Exception
     */
    function getArticle($articleIdOrSlug): Article
    {
        $filter = (preg_match('/^[A-Z0-9]{32}$/', $articleIdOrSlug) === 1 ? 'id' : 'slug');
        $endPoint = 'article/' . $filter . '/' . $articleIdOrSlug;
        return new Article($this->getWrapper($endPoint));
    }

    /**
     * @throws Exception
     */
    function getImages(): array
    {
        $images = $this->getWrapper('image');
        $result = [];
        foreach ($images as $image){
            $result[] = new Image($image);
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    function registerImage(string $pathOrBase64, $mode='external')
    {
        return new Image($this->hasBodyWrapper('image', $pathOrBase64));
    }

    /**
     * @throws Exception
     */
    function getCategories()
    {
        $categories =  $this->getWrapper('category');
        $result = [];
        foreach ($categories as $category){
            $result[] = new Category($category);
        }
        return $result;
    }

    /**
     * @return mixed
     */
    function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @param $endpoint
     * @param $body
     * @param string $method
     * @return mixed
     * @throws Exception
     */
    private function hasBodyWrapper($endpoint, $body, $method='POST')
    {
        $jsonBody = json_encode($body);
        $request = new Request($method, $endpoint,[
            'Authorization' => 'Bearer ' . $this->token
        ],$jsonBody);
        try{
            return $this->retrieveResult($this->guzzleClient->send($request));
        }catch (GuzzleHttp\Exception\ClientException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }


    /**
     * @throws Exception
     */
    private function getWrapper($endPoint)
    {
        try {
            $guzzle = $this->guzzleClient->get($endPoint, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token
                ]
            ]);
            return $this->retrieveResult($guzzle);
        } catch (GuzzleHttp\Exception\ClientException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return bool
     * @throws Exception
     */
    function authenticate()
    {
        try {

            $results = $this->retrieveResult($this->guzzleClient->post('auth/' . $this->privateKey . '/' . $this->publicKey));
            if (isset($results['token'])) {
                $this->token = $results['token'];
            }
            if (isset($results['user'])) {
                $this->currentUser = $results['user'];
            }
        } catch (ClientException $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
        return true;
    }

    private function articleResults($articles):array
    {
        $result = [];
        foreach ($articles as $article){
            $result[] = new Article($article);
        }
        return $result;
    }


    /**
     * @param Response $call
     *
     * @return mixed
     */
    private function retrieveResult(Response $call)
    {
        $res = $call->getBody()->getContents();
        return json_decode($res, true);
    }


}