<?php

namespace MoySklad\Repositories;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Account;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Cashier;
use MoySklad\Entities\ContactPerson;
use MoySklad\Entities\Contract;
use MoySklad\Entities\Counterparty;
use MoySklad\Entities\Country;
use MoySklad\Entities\Currency;
use MoySklad\Entities\Discount;
use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Entities\Documents\Cash\AbstractCash;
use MoySklad\Entities\Documents\Cash\CashIn;
use MoySklad\Entities\Documents\Cash\CashOut;
use MoySklad\Entities\Documents\Movements\AbstractMovement;
use MoySklad\Entities\Documents\Movements\Demand;
use MoySklad\Entities\Documents\Movements\Enter;
use MoySklad\Entities\Documents\Movements\Loss;
use MoySklad\Entities\Documents\Movements\Supply;
use MoySklad\Entities\Documents\Orders\AbstractOrder;
use MoySklad\Entities\Documents\Orders\CustomerOrder;
use MoySklad\Entities\Documents\Orders\PurchaseOrder;
use MoySklad\Entities\Documents\Positions\AbstractPosition;
use MoySklad\Entities\Documents\Positions\CustomerOrderPosition;
use MoySklad\Entities\Documents\Positions\EnterPosition;
use MoySklad\Entities\Documents\Retail\AbstractRetail;
use MoySklad\Entities\Documents\Retail\RetailDemand;
use MoySklad\Entities\Documents\Retail\RetailSalesReturn;
use MoySklad\Entities\Documents\RetailDrawer\AbstractRetailDrawer;
use MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashIn;
use MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashOut;
use MoySklad\Entities\Documents\Returns\AbstractReturn;
use MoySklad\Entities\Documents\Returns\PurchaseReturn;
use MoySklad\Entities\Documents\Returns\SalesReturn;
use MoySklad\Entities\Employee;
use MoySklad\Entities\ExpenseItem;
use MoySklad\Entities\Folders\ProductFolder;
use MoySklad\Entities\Group;
use MoySklad\Entities\Misc\Attribute;
use MoySklad\Entities\Misc\Characteristics;
use MoySklad\Entities\Misc\CompanySettings;
use MoySklad\Entities\Misc\CustomEntity;
use MoySklad\Entities\Misc\State;
use MoySklad\Entities\Misc\Webhook;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Products\Consignment;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Entities\Products\Variant;
use MoySklad\Entities\Project;
use MoySklad\Entities\RetailStore;
use MoySklad\Entities\Store;
use MoySklad\Entities\Uom;
use MoySklad\Utils\AbstractSingleton;

class EntityRepository extends AbstractSingleton{
    protected static $instance = null;
    public $entities = [
        AbstractEntity::class,
        AbstractDocument::class,
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
        AbstractPosition::class,
        EnterPosition::class,
        CustomerOrderPosition::class,
        Country::class,
        Webhook::class,
        ProductFolder::class,
        Consignment::class,
        Variant::class,
        AbstractMovement::class,
        Enter::class,
        Attribute::class,
        Store::class,
        Characteristics::class,
        CompanySettings::class,
        CustomEntity::class,
        Cashier::class,
        Contract::class,
        Discount::class,
        ExpenseItem::class,
        Project::class,
        RetailStore::class,
        Currency::class,
        Loss::class,
        Demand::class,
        Supply::class,
        AbstractCash::class,
        CashIn::class,
        CashOut::class,
        AbstractRetail::class,
        RetailSalesReturn::class,
        RetailDemand::class,
        AbstractRetailDrawer::class,
        RetailDrawerCashIn::class,
        RetailDrawerCashOut::class,
        AbstractReturn::class,
        PurchaseReturn::class,
        SalesReturn::class
    ];
    public $entityNames = [];

    protected function __construct()
    {
        foreach ($this->entities as $i=>$e){
            $this->entityNames[$e::$entityName] = $e;
        }
    }
}