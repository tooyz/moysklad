<?php

namespace MoySklad\Entities;

use MoySklad\Traits\DoesCreation;

class Counterparty extends AbstractEntity{
    use DoesCreation;
    public static
        $entityName = 'counterparty';
}