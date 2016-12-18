<?php

namespace MoySklad\Components\Specs;


class CreationSpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    public function getDefaults()
    {
        return [
          "multiple" => false
        ];
    }
}