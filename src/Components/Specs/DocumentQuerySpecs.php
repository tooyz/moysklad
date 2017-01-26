<?php

namespace MoySklad\Components\Specs;


class DocumentQuerySpecs extends QuerySpecs {

    public function getDefaults(){
        $res = parent::getDefaults();
        $res->{'state.name'} = null;
        $res->{'state.id'} = null;
        $res->{'organization.id'} = null;
        $res->isDeleted = null;
        return $res;
    }
}