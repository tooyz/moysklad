<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Exceptions\ApiResponseException;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Lists\EntityList;
use MoySklad\Repositories\RequestUrlRepository;

trait LinksPositionsForCreation{

    protected function linkPositionsForCreation(EntityList $positions){
        if ( $positions ){
            $positions = new EntityList($this->getSkladInstance(), $positions);
            $positions->each(function(AbstractProduct $position){
                $position->assortment = [
                    'meta' => $position->getMeta()
                ];
                $this->links->link($position, LinkingSpecs::create([
                    'multiple' => true,
                    'name' => "positions",
                    'excludedFields' => [
                        'id', 'meta'
                    ]
                ]));
            });
        }
    }
}