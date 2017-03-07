<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Repositories\ApiUrlRepository;

class UpdateBuilder extends AbstractMutationBuilder {
    /**
     * Update entity with current fields
     * @param boolean $getIdFromMeta
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     */
    function execute()
    {
        $entity = &$this->e;
        $entityClass = get_class($entity);
        if ( empty($entity->fields->id) ){
            if ( !$id = $entity->getMeta()->getId()) throw new EntityHasNoIdException($entity);
        } else {
            $id = $entity->id;
        }
        $res = $entity->getSkladInstance()->getClient()->put(
            ApiUrlRepository::instance()->getUpdateUrl($entityClass::$entityName, $id),
            $entity->mergeFieldsWithLinks()
        );
        return new $entityClass($entity->getSkladInstance(), $res);

    }
}