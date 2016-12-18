<?php

namespace MoySklad\Components\Specs;


class ConstructionSpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    public function getDefaults()
    {
        return [
          "relations" => true
        ];
    }
}