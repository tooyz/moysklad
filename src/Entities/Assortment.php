<?php

namespace MoySklad\Entities;

use MoySklad\MoySklad;
use MoySklad\Traits\CreatesSimply;

class Assortment extends AbstractEntity{
    use CreatesSimply;

    public static $entityName = 'assortment';

}