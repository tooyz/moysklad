<?php

namespace MoySklad\Traits;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Repositories\RequestUrlRepository;

trait Updates{

    /**
     * Update entity with current fields
     * @param boolean $getIdFromMeta
     * @return static
     * @throws EntityHasNoIdException
     */
    public function update($getIdFromMeta = false){
        /**
         * @var AbstractEntity $this
         */
        if ( empty($this->fields->id) ){
            if ( !$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $res = $this->getSkladInstance()->getClient()->put(
            RequestUrlRepository::instance()->getUpdateUrl(static::$entityName, $id),
            $this->mergeFieldsWithLinks()
        );
        return new static($this->getSkladInstance(), $res);
    }
}