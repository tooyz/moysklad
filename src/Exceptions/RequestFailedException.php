<?php

namespace MoySklad\Exceptions;

use \Exception;

class RequestFailedException extends Exception{
    protected
        $request,
        $response;

    public function __construct($request, $response, $code = 0, Exception $previous = null)
    {
        parent::__construct(
            '',
            $code,
            $previous);
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest(){
        return $this->request;
    }

    public function getResponse(){
        return $this->response;
    }

    public function getDump(){
        return [
          "request" => $this->request,
          "response" => $this->response
        ];
    }
}