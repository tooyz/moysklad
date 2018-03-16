<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Registers\ApiUrlRegistry;

class UpdateBuilder extends AbstractMutationBuilder {
    /**
     * Update entity with current fields
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     * @throws \Throwable
     */
    public function execute()
    {
        $entity = &$this->e;
        $entityClass = get_class($entity);
        $id = $entity->findEntityId();
        $res = $entity->getSkladInstance()->getClient()->put(
            ApiUrlRegistry::instance()->getUpdateUrl($entityClass::$entityName, $id),
            $entity->mergeFieldsWithLinks()
        );
        return new $entityClass($entity->getSkladInstance(), $res);
    }
}
