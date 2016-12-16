<?php

namespace Tests;

abstract class Config{
    public static function getAuthData(){
        return (object)[
            "login" => "admin@tooy_m",
            "password" => "zaika45"
        ];
    }
}