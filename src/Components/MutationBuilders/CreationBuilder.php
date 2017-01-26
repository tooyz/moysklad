<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Components\MassRequest;
use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Exceptions\IncompleteCreationFieldsException;

class CreationBuilder extends AbstractMutationBuilder {
    /**
     * @var CreationSpecs
     */
    protected $specs;

    public function __construct(AbstractEntity &$entity, CreationSpecs &$specs = null){
        parent::__construct($entity);
        if ( !$specs ) $specs = CreationSpecs::create();
        $this->specs = $specs;
    }

    public function execute()
    {
        $eClass = get_class($this->e);
        $requiredFields = $eClass::getFieldsRequiredForCreation();
        $mr = new MassRequest($this->e->getSkladInstance(), $this->e);
        foreach ( $requiredFields as $requiredField ){
            if ( !isset($this->e->{$requiredField}) ) throw new IncompleteCreationFieldsException($this->e);
        }
        return $mr->create()[0];
    }
}