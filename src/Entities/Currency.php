<?php

namespace MoySklad\Entities;


use MoySklad\Traits\HasPlainCreation;

class Currency extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'currency';
}