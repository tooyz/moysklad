<?php

namespace MoySklad\Entities;


use MoySklad\Traits\HasPlainCreation;

class Counterparty extends AbstractEntity{
    use HasPlainCreation;

    public static
        $entityName = 'counterparty';
}