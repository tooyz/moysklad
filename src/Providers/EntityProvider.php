<?php

namespace MoySklad\Providers;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Account;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\ContactPerson;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Country;
use MoySklad\Entities\Employee;
use MoySklad\Entities\Folders\ProductFolder;
use MoySklad\Entities\Group;
use MoySklad\Entities\Misc\State;
use MoySklad\Entities\Misc\Webhook;
use MoySklad\Entities\Orders\AbstractOrder;
use MoySklad\Entities\Orders\CustomerOrder;
use MoySklad\Entities\Orders\Positions\CustomerOrderPosition;
use MoySklad\Entities\Orders\PurchaseOrder;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Entities\Uom;
use MoySklad\Utils\AbstractSingleton;

class EntityProvider extends AbstractSingleton{
    protected static $instance = null;
    public $entities = [
        AbstractEntity::class,
        AbstractOrder::class,
        CustomerOrder::class,
        PurchaseOrder::class,
        Assortment::class,
        Counterparty::class,
        Organization::class,
        AbstractProduct::class,
        Product::class,
        Service::class,
        Employee::class,
        Group::class,
        Uom::class,
        Account::class,
        ContactPerson::class,
        State::class,
        CustomerOrderPosition::class,
        Country::class,
        Webhook::class,
        ProductFolder::class
    ];
    public $entityNames = [];

    protected function __construct()
    {
        foreach ($this->entities as $i=>$e){
            $this->entityNames[$e::$entityName] = $e;
        }
    }
}