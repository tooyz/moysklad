<?php

namespace MoySklad\Entities;

use MoySklad\MoySklad;
use MoySklad\Traits\HasPlainCreation;

class Assortment extends AbstractEntity{
    use HasPlainCreation;

    public static $entityName = 'assortment';

}