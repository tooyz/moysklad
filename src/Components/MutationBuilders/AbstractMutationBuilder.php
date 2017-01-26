<?php

namespace MoySklad\Components\MutationBuilders;

use MoySklad\Components\Specs\CreationSpecs;
use MoySklad\Components\Specs\LinkingSpecs;
use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Account;
use MoySklad\Entities\Cashier;
use MoySklad\Entities\Counterparty;

abstract class AbstractMutationBuilder{
    /**
     * @var AbstractEntity
     */
    protected $e;
    /**
     * @var CreationSpecs
     */
    protected $specs;

    public function __construct(AbstractEntity &$entity, CreationSpecs &$specs){
        $this->e = $entity;
        $this->specs = $specs;
    }

    public function addAccount(Account $account){
        return $this->simpleLink($account);
    }

    public function addCounterparty(Counterparty $counterparty){
        return $this->simpleLink($counterparty, LinkingSpecs::create([
            "name" => "agent"
        ]));
    }

    protected function simpleLink(AbstractEntity $linkedEntity, LinkingSpecs $specs = null){
        $this->e->links->link($linkedEntity, $specs);
        return $this;
    }

    abstract function run();
}