<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\EntityHasNoIdException;
use MoySklad\Registers\ApiUrlRegistry;

class UpdateBuilder extends AbstractMutationBuilder {
    /**
     * Update entity with current fields
     * @param bool $customEntity
     * @return AbstractEntity
     * @throws EntityHasNoIdException
     * @throws \Throwable
     */
    public function execute($customEntity = false)
    {
        $entity = &$this->e;
        $entityClass = get_class($entity);
        $id = $entity->findEntityId($customEntity);
        $res = $entity->getSkladInstance()->getClient()->put(
            ApiUrlRegistry::instance()->getUpdateUrl($entityClass::$entityName, $id),
            $entity->mergeFieldsWithLinks()
        );
        return new $entityClass($entity->getSkladInstance(), $res);
    }
}
