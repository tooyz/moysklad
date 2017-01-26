<?php

namespace MoySklad\Entities;

use MoySklad\Traits\CreatesSimply;

class ExpenseItem extends AbstractEntity{

    use CreatesSimply;

    public static
        $entityName = 'expenseitem';
}