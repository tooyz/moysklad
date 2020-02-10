<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Attribute extends AbstractEntity  {
    public static $entityName = 'attributemetadata';

    public function jsonSerialize()
    {
        $res = $this->fields->getInternal();
        $res->relations = $this->relations;
        $res->meta = $this->meta;
        return $res;
    }

}
