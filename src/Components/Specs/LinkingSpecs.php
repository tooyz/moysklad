<?php

namespace MoySklad\Components\Specs;


class LinkingSpecs extends AbstractSpecs {
    public function getDefaults()
    {
        return [
            'name' => null,
            'fields' => null,
            "multiple" => false
        ];
    }
}