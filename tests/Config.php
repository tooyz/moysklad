<?php

namespace Tests;

abstract class Config{
    public static function getAuthData(){
        return (object)[
            "login" => "",
            "password" => ""
        ];
    }
}