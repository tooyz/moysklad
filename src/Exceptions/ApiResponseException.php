<?php

namespace MoySklad\Exceptions;

/**
 * Request did not fail, but response contained "errors" field
 * Class ApiResponseException
 * @package MoySklad\Exceptions
 */
class ApiResponseException extends RequestFailedException{
    protected
        $code,
        $errorText,
        $moreInfo;

    public function __construct($request, $response)
    {
        parent::__construct($request, $response);
        $error = is_array($response) ? $response[0]->errors[0] : $response->errors[0];
        $this->code = $error->code;
        $this->errorText = $error->error;
        $this->moreInfo = $error->moreInfo;
    }

    public function getApiCode(){
        return $this->code;
    }

    public function getErrorText(){
        return $this->errorText;
    }

    public function getMoreInfo(){
        return $this->moreInfo;
    }
}
