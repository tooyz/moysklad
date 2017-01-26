<?php

namespace MoySklad\Entities\Documents\Positions;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Traits\CreatesSimply;

class AbstractPosition extends AbstractDocument{
    use CreatesSimply;
    public static $entityName = 'a_position';
}