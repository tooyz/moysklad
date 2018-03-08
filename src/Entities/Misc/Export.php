<?php

namespace MoySklad\Entities\Misc;

use MoySklad\Entities\AbstractEntity;

class Export extends AbstractEntity  {
    public static $entityName = 'export';

    public static function getFieldsRequiredForCreation()
    {
        return ["extension"];
    }

    public function getFileLink(){
        return $this->fields->file;
    }
}
