<?php

namespace MoySklad\Traits;

use MoySklad\Components\Fields\ImageField;
use MoySklad\Entities\AbstractEntity;

trait AttachesImage{
    public function attachImage(ImageField $imageField){
        /**
         * @var AbstractEntity $this
         */
        $this->fields->image = $imageField;
    }
}
