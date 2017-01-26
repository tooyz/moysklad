<?php

namespace MoySklad\Entities\Products;


use MoySklad\Traits\CreatesSimply;

class Product extends AbstractProduct {
    use CreatesSimply;

    public static
        $entityName = 'product';
}