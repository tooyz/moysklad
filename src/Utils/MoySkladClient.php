<?php

namespace MoySklad\Utils;

use GuzzleHttp\Client;

class MoySkladClient{
    private
        $endpoint = "https://online.moysklad.ru/api/remap/1.1/",
        $login,
        $password;

    public function __construct($login, $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function get($method, $payload){
        return $this->get(
            'GET',
            $this->endpoint . $method,
            $payload
        );
    }

    public function post($method, $payload){

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
            "body" => $data
        ];

        $client = new Client($requestOptions);
        $res = $client->request(
            $requestType,
            $apiMethod
        );

        return $res->getBody();
    }
}