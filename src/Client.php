<?php


namespace BluaBlue;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private $apiUrl;
    private $userName;
    private $password;
    private $guzzleClient;
    private $token;
    private $currentUser;

    function __construct($userName, $password, $baseUrl = 'https://blua.blue/api.v1/')
    {
        $this->apiUrl = $baseUrl;
        $this->userName = $userName;
        $this->password = $password;
        $this->guzzleClient = new \GuzzleHttp\Client([
            'base_uri' => $baseUrl,
            'timeout' => 2.0
        ]);
        $this->authenticate();
    }

    function getArticleList($offset = 0, $limit = 100, $author = null)
    {
        $endPoint = 'articleList?orderBy=date' . ($author ? '&author=' . $author : '');
        try{
            return $this->retrieveResult($this->guzzleClient->get($endPoint, [
                'headers' => [
                    'Authorization'     => 'Bearer ' . $this->token
                ]
            ]));
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), 401);
        }

    }

    function getArticle($articleIdOrSlug)
    {
        $filter = (preg_match('/^[A-Z0-9]{32}$/', $articleIdOrSlug) === 1 ? 'id' : 'slug');
        $endPoint = 'article?' . $filter . '=' . $articleIdOrSlug;
        try{
            return $this->retrieveResult($this->guzzleClient->get($endPoint, [
                'headers' => [
                    'Authorization'     => 'Bearer ' . $this->token
                ]
            ]));
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), 401);
        }
    }

    private function authenticate()
    {
        try {

            $results = $this->retrieveResult($this->guzzleClient->post('login', [
                'json' => [
                    'userName' => $this->userName,
                    'password' => $this->password
                ]
            ]));
            if (isset($results['token'])) {
                $this->token = $results['token'];
            }
            if (isset($results['user'])) {
                $this->currentUser = $results['user'];
            }
        } catch (ClientException $e) {
            throw new \Exception($e->getMessage(), 401);
        }
        return true;
    }

    private function retrieveResult(ResponseInterface $call)
    {
        return json_decode($call->getBody()
                                ->getContents(), true);
    }


}