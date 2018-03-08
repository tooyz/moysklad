<?php

namespace MoySklad\Entities\Documents\Templates;

use MoySklad\Entities\AbstractEntity;

class AbstractTemplate extends AbstractEntity  {
    public static $entityName = 'a_template';

    public function getContent(){
        return $this->fields->content;
    }
}
