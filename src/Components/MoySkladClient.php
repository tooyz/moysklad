<?php

namespace MoySklad\Components;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use MoySklad\Exceptions\RequestFailedException;
use MoySklad\Exceptions\ResponseParseException;

class MoySkladClient{
    const PRE_REQUEST_SLEEP_TIME = 200;

    private
        $endpoint = "https://online.moysklad.ru/api/remap/1.1/",
        $login,
        $password;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function get($method, $payload = []){
        return $this->makeRequest(
            'GET',
            $method,
            $payload
        );
    }

    public function post($method, $payload = []){
        return $this->makeRequest(
            'POST',
            $method,
            $payload
        );
    }

    public function put($method, $payload = []){
        return $this->makeRequest(
            'PUT',
            $method,
            $payload
        );
    }

    private function makeRequest(
        $requestType,
        $apiMethod,
        $data = [],
        $options = []
    ){
        $requestOptions = [
            "base_uri" => $this->endpoint,
            "headers" => [
                "Authorization" => "Basic " . base64_encode($this->login . ':' . $this->password)
            ],
        ];
        $requestBody = [];
        if ( $requestType === 'GET' ){
            $requestBody['query'] = $data;
        } else if ( $requestType === 'POST' ||  $requestType === 'PUT' ){
            $requestBody['json'] = $data;
        }

        $client = new Client($requestOptions);
        try{
            usleep(self::PRE_REQUEST_SLEEP_TIME);
            $res = $client->request(
                $requestType,
                $apiMethod,
                $requestBody
            );
            if ( is_null($result = \json_decode($res->getBody())) === false ){
                return $result;
            } else {
                throw new ResponseParseException($res);
            }
        } catch (ClientException $e){
            $res = "REQUEST: " . $e->getRequest()->getBody() . "\n\n".
                   "RESPONSE: ". $e->getResponse()->getBody() . "\n\n";
            echo $res;
            throw new RequestFailedException();
        }
    }
}