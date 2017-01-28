<?php

namespace MoySklad\Components\Specs\QuerySpecs;


class DocumentQuerySpecs extends QuerySpecs {
    protected static $cachedDefaultSpecs = null;
    public function getDefaults(){
        $res = parent::getDefaults();
        $res->{'state.name'} = null;
        $res->{'state.id'} = null;
        $res->{'organization.id'} = null;
        $res->isDeleted = null;
        return $res;
    }
}