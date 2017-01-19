<?php

namespace MoySklad\Components\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use MoySklad\Exceptions\RequestFailedException;
use MoySklad\Exceptions\ResponseParseException;

class MoySkladClient{
    const
        METHOD_GET = "GET",
        METHOD_POST = "POST",
        METHOD_PUT = "PUT",
        METHOD_DELETE = "DELETE",
        HTTP_CODE_SUCCESS = 200;

    private $preRequestSleepTime = 200;

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
            self::METHOD_GET,
            $method,
            $payload
        );
    }

    public function post($method, $payload = []){
        return $this->makeRequest(
            self::METHOD_POST,
            $method,
            $payload
        );
    }

    public function put($method, $payload = []){
        return $this->makeRequest(
            self::METHOD_PUT,
            $method,
            $payload
        );
    }

    public function delete($method, $payload = []){
        return $this->makeRequest(
            self::METHOD_DELETE,
            $method,
            $payload
        );
    }

    public function getLastRequest(){
        return RequestLog::getLast();
    }

    public function getRequestList(){
        return RequestLog::getList();
    }

    public function setPreRequestTimeout($ms){
        $this->preRequestSleepTime = $ms;
    }

    private function makeRequest(
        $requestHttpMethod,
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
        $jsonRequestsTypes = [
            self::METHOD_POST,
            self::METHOD_PUT,
            self::METHOD_DELETE
        ];
        $requestBody = [];
        if ( $requestHttpMethod === self::METHOD_GET ){
            $requestBody['query'] = $data;
        } else if ( in_array($requestHttpMethod, $jsonRequestsTypes) ){
            $requestBody['json'] = $data;
        }

        $client = new Client($requestOptions);
        try{
            usleep($this->preRequestSleepTime);
            $res = $client->request(
                $requestHttpMethod,
                $apiMethod,
                $requestBody
            );
            if ( $res->getStatusCode() === self::HTTP_CODE_SUCCESS ){
                $serializedRequest = (isset($requestBody['json'])?\json_decode(\json_encode($requestBody['json'])):$requestBody['query']);
                $reqLog = [
                    "req" => [
                        "type" => $requestHttpMethod,
                        "method" => $apiMethod,
                        "body" => $serializedRequest
                    ]
                ];
                if ( $requestHttpMethod !== self::METHOD_DELETE ){
                    if ( is_null($result = \json_decode($res->getBody())) === false ){
                        $reqLog['res'] = $result;
                        RequestLog::add($reqLog);
                        return $result;
                    } else {
                        throw new ResponseParseException($res);
                    }
                }
                RequestLog::add($reqLog);
            }
        } catch (ClientException $e){
            $res = "REQUEST: " . $e->getRequest()->getBody() . "\n\n".
                   "RESPONSE: ". $e->getResponse()->getBody() . "\n\n";
            echo $res;
            throw new RequestFailedException();
        }
    }
}