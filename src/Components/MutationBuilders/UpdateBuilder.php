<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Repositories\ApiUrlRegistry;

class UpdateBuilder extends AbstractMutationBuilder {
    /**
     * Update entity with current fields
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
            ApiUrlRegistry::instance()->getUpdateUrl($entityClass::$entityName, $id),
            $entity->mergeFieldsWithLinks()
        );
        return new $entityClass($entity->getSkladInstance(), $res);

    }
}