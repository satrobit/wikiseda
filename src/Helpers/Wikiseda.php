<?php
namespace Wikiseda\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

Class Wikiseda {

    protected $client;
    protected $lang;

    /**
     * Wikiseda constructor.
     */
    function __construct()
    {
        $this->lang = "en";

        $this->client = new Client([
            'base_uri' => 'https://getsongg.com/dapp/']);
    }


    /**
     * @param $method
     * @param $url
     * @param $page
     * @param $parameters
     * @param null $key
     * @return array|bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getResults($method, $url, $page, $parameters, $key = null)
    {
        $resultArray = [];

        $parameters['page'] = $page;
        $parameters['lang'] = $this->lang;

        if ($page === 0) // get all pages
        {
            $pageNumebr = 1;
            while(true)
            {
                $parameters['page'] = $pageNumebr;

                try {
                    $response = $this->client->request($method, $url, ['query' => $parameters]);
                } catch (RequestException $e) {
                    echo Psr7\str($e->getRequest());
                    if ($e->hasResponse()) {
                        echo Psr7\str($e->getResponse());
                    }

                    return false;
                }

                if ($response->getStatusCode() !== 200) continue;

                $body = (string) $response->getBody();

                $bodyArray = json_decode($body, true);

                if (!is_null($key)) $bodyArray = $bodyArray[$key];

                if (count($bodyArray) === 0 || !is_array($bodyArray)) break;

                $resultArray = array_merge($resultArray, $bodyArray);

                $pageNumebr++;
            }
        } else {

            try {
                $response = $this->client->request($method, $url, ['query' => $parameters]);
            } catch (RequestException $e) {
                echo Psr7\str($e->getRequest());
                if ($e->hasResponse()) {
                    echo Psr7\str($e->getResponse());
                }

                return false;
            }

            if ($response->getStatusCode() !== 200) return false;

            $body = (string) $response->getBody();

            $bodyArray = json_decode($body, true);

            if (!is_null($key)) $bodyArray = $bodyArray[$key];

            $resultArray = $bodyArray;

        }

        return $resultArray;
    }


    /**
     * @param string $query
     * @param int $page
     * @param string $order
     * @param string $type
     * @return array|bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function  artist($query, $page = 1, $order = 'top', $type = 'album')
    {
        return $this->getResults('GET', 'getnewcases', $page,[
            'signer_id' => urlencode($query),
            'order' => $order,
            'type' => $type
        ], 'items');
    }

    /**
     * @param string $query
     * @param int $page
     * @param string $order
     * @param string $type
     * @return array|bool|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search($query, $page = 1, $order = 'top', $type = 'all')
    {
        return $this->getResults('GET', '', $page,[
            'query' => urlencode($query),
            'order' => $order,
            'type' => $type
        ]);
    }
}