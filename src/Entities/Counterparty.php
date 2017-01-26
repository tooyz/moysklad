<?php

namespace MoySklad\Entities;


use MoySklad\Traits\CreatesSimply;

class Counterparty extends AbstractEntity{
    use CreatesSimply;

    public static
        $entityName = 'counterparty';
}