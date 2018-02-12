<?php

namespace MoySklad\Components\Specs;


class EmptySpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;

    /**
     * Get possible variables for spec
     * @return array
     */
    public function getDefaults()
    {
        return [];
    }
}
