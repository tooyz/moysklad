<?php

namespace MoySklad\Entities\Products;

use MoySklad\Exceptions\IncompatibleFieldsException;
use MoySklad\Traits\AttachesImage;
use MoySklad\Traits\RequiresOnlyNameForCreation;

class Product extends AbstractProduct {
    use AttachesImage, RequiresOnlyNameForCreation;

    public static
        $entityName = 'product';

    public function makeAlcoholic($excise = null, $type = null, $strength = null, $volume = null){
        if ( isset($this->fields->isSerialTrackable) ){
            throw new IncompatibleFieldsException("alcoholic", "isSerialTrackable");
        }
        $this->fields->alcoholic = new \stdClass();
        if ( $excise ) $this->fields->alcoholic->excise = $excise;
        if ( $type ) $this->fields->alcoholic->type = $type;
        if ( $strength ) $this->fields->alcoholic->strength = $strength;
        if ( $volume ) $this->fields->alcoholic->volume = $volume;
        return $this;
    }
}