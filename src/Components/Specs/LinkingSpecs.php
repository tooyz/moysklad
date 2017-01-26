<?php

namespace MoySklad\Components\Specs;


class LinkingSpecs extends AbstractSpecs {
    protected static $cachedDefaultSpecs = null;
    /**
     * Get possible variables for spec
     *  name: what name to use when linking
     *  fields: what fields will be used when linking, others will be discarded
     *  excludeFields: what fields will be discarded when linking, can't be used with "fields" param
     *  multiple: flags if same named links should be put into array
     * @return array
     */
    public function getDefaults()
    {
        return [
            'name' => null,
            'fields' => null,
            'excludedFields' => null,
            'multiple' => false
        ];
    }
}