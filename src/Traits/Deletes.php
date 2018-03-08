<?php

namespace MoySklad\Traits;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\ApiResponseException;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Registers\ApiUrlRegistry;

trait Deletes{


    /**
     * Delete entity, throws exception if not found
     * @param bool $getIdFromMeta
     * @return bool
     * @throws EntityHasNoIdException
     */
    public function delete($getIdFromMeta = false){
        /**
         * @var AbstractEntity $this
         */
        if ( empty($this->fields->id) ){
            if ( !$getIdFromMeta || !$id = $this->getMeta()->getId()) throw new EntityHasNoIdException($this);
        } else {
            $id = $this->id;
        }
        $this->getSkladInstance()->getClient()->delete(
            ApiUrlRegistry::instance()->getDeleteUrl(static::$entityName, $id)
        );
        return true;
    }
}
