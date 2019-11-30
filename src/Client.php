<?php


namespace BluaBlue;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package BluaBlue
 */
class Client
{
    /**
     * @var string
     */
    private $apiUrl;
    /**
     * @var
     */
    private $userName;
    /**
     * @var
     */
    private $password;
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
     * @param        $userName
     * @param        $password
     * @param string $baseUrl
     *
     * @throws \Exception
     */
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

    /**
     * @param int  $offset
     * @param int  $limit
     * @param null $author
     *
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * @param $articleIdOrSlug
     *
     * @return mixed
     * @throws \Exception
     */
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

    /**
     * @return bool
     * @throws \Exception
     */
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

    /**
     * @param ResponseInterface $call
     *
     * @return mixed
     */
    private function retrieveResult(ResponseInterface $call)
    {
        return json_decode($call->getBody()
                                ->getContents(), true);
    }


}