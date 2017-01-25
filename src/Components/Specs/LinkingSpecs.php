<?php

namespace MoySklad\Components\Specs;


class LinkingSpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    /**
     * Get possible variables for spec
     *  name: what name to use when linking
     *  fields: what fields will be used when linking, others will be discarded
     *  multiple: flags if same named links should be put into array
     * @return array
     */
    public function getDefaults()
    {
        return [
            'name' => null,
            'fields' => null,
            'multiple' => false
        ];
    }
}