<?php

namespace MoySklad\Entities;

use MoySklad\Traits\RequiresOnlyNameForCreation;

class ExpenseItem extends AbstractEntity{
    use RequiresOnlyNameForCreation;
    public static $entityName = 'expenseitem';
}