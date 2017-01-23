<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Traits\DoesCreation;

class AbstractProduct extends AbstractEntity{
    use DoesCreation;

    public static $entityName = '_a_product';

    /**
     * @param $name
     * @return null|\stdClass
     */
    public function getSalePrice($name){
        if ( empty($this->salePrices) ) return null;
        foreach ( $this->salePrices as $sp ){
            if ( $sp->priceType == $name ){
                return $sp;
            }
        }
        return null;
    }
}