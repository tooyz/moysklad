<?php

namespace MoySklad\Entities\Products;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Documents\AbstractDocument;

class AbstractProduct extends AbstractDocument{
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
