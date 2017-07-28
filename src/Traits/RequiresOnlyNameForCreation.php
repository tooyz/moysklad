<?php

namespace MoySklad\Traits;

trait RequiresOnlyNameForCreation{
    public static function getFieldsRequiredForCreation()
    {
        return ["name"];
    }
}
