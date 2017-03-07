<?php

namespace MoySklad\Entities;

class Currency extends AbstractEntity{
    public static $entityName = 'currency';

    public static function getFieldsRequiredForCreation(){
        return ["name", "code", "isoCode"];
    }
}