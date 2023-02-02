<?php

namespace MoySklad\Components\Specs\QuerySpecs;

class AssortmentQuerySpecs extends QuerySpecs {
    protected static $cachedDefaultSpecs = null;
    public function getDefaults(){
        $res = parent::getDefaults();
        $res['filter'] = null;
        $res['stockstore'] = null;
        $res['stockmoment'] = null;
        $res['scope'] = null;
        $res['stockmode'] = null;
        return $res;
    }
}
