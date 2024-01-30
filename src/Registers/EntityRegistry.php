<?php

namespace MoySklad\Registers;

use MoySklad\Entities\AbstractEntity;
use MoySklad\Entities\Account;
use MoySklad\Entities\Assortment;
use MoySklad\Entities\Audit\Audit;
use MoySklad\Entities\Audit\AuditEvent;
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
use MoySklad\Entities\Documents\CommissionReports\AbstractCommissionReport;
use MoySklad\Entities\Documents\CommissionReports\CommissionReportIn;
use MoySklad\Entities\Documents\CommissionReports\CommissionReportOut;
use MoySklad\Entities\Documents\Factures\AbstractFacture;
use MoySklad\Entities\Documents\Factures\FactureIn;
use MoySklad\Entities\Documents\Factures\FactureOut;
use MoySklad\Entities\Documents\Inventory;
use MoySklad\Entities\Documents\Movements\AbstractMovement;
use MoySklad\Entities\Documents\Movements\Demand;
use MoySklad\Entities\Documents\Movements\Enter;
use MoySklad\Entities\Documents\Movements\Loss;
use MoySklad\Entities\Documents\Movements\Supply;
use MoySklad\Entities\Documents\Movements\Move;
use MoySklad\Entities\Documents\Payments\PaymentOut;
use MoySklad\Entities\Documents\Payments\PaymentIn;
use MoySklad\Entities\Documents\Orders\AbstractOrder;
use MoySklad\Entities\Documents\Orders\CustomerOrder;
use MoySklad\Entities\Documents\Orders\PurchaseOrder;
use MoySklad\Entities\Documents\Positions\AbstractPosition;
use MoySklad\Entities\Documents\Positions\CustomerOrderPosition;
use MoySklad\Entities\Documents\Positions\InventoryPosition;
use MoySklad\Entities\Documents\Positions\DemandPosition;
use MoySklad\Entities\Documents\Positions\EnterPosition;
use MoySklad\Entities\Documents\Positions\InvoicePosition;
use MoySklad\Entities\Documents\Positions\LossPosition;
use MoySklad\Entities\Documents\Positions\MovePosition;
use MoySklad\Entities\Documents\Positions\SalesReturnPosition;
use MoySklad\Entities\Documents\Positions\PurchaseReturnPosition;
use MoySklad\Entities\Documents\Positions\SupplyPosition;
use MoySklad\Entities\Documents\Positions\PurchaseOrderPosition;
use MoySklad\Entities\Documents\PriceLists\PriceList;
use MoySklad\Entities\Documents\PriceLists\PriceListRow;
use MoySklad\Entities\Documents\Processings\ProcessingMaterial;
use MoySklad\Entities\Documents\Processings\ProcessingPlanFolder;
use MoySklad\Entities\Documents\Processings\ProcessingPlanMaterial;
use MoySklad\Entities\Documents\Processings\ProcessingPlanProduct;
use MoySklad\Entities\Documents\Processings\ProcessingProduct;
use MoySklad\Entities\Documents\Templates\CustomTemplate;
use MoySklad\Entities\Products\Components\AbstractComponent;
use MoySklad\Entities\Products\Components\BundleComponent;
use MoySklad\Entities\Documents\Processings\AbstractProcessing;
use MoySklad\Entities\Documents\Processings\Processing;
use MoySklad\Entities\Documents\Processings\ProcessingOrder;
use MoySklad\Entities\Documents\Processings\ProcessingPlan;
use MoySklad\Entities\Documents\Retail\AbstractRetail;
use MoySklad\Entities\Documents\Retail\RetailDemand;
use MoySklad\Entities\Documents\Retail\RetailSalesReturn;
use MoySklad\Entities\Documents\RetailDrawer\AbstractRetailDrawer;
use MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashIn;
use MoySklad\Entities\Documents\RetailDrawer\RetailDrawerCashOut;
use MoySklad\Entities\Documents\RetailShift;
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
use MoySklad\Entities\Misc\Publication;
use MoySklad\Entities\Misc\State;
use MoySklad\Entities\Misc\PriceType;
use MoySklad\Entities\Misc\Webhook;
use MoySklad\Entities\Misc\SalesChannel;
use MoySklad\Entities\Organization;
use MoySklad\Entities\Products\AbstractProduct;
use MoySklad\Entities\Products\Bundle;
use MoySklad\Entities\Products\Consignment;
use MoySklad\Entities\Products\Product;
use MoySklad\Entities\Products\Service;
use MoySklad\Entities\Products\Variant;
use MoySklad\Entities\Project;
use MoySklad\Entities\RetailStore;
use MoySklad\Entities\Store;
use MoySklad\Entities\Uom;
use MoySklad\Entities\Bonustransaction;
use MoySklad\Entities\Bonusprogram;
use MoySklad\Utils\AbstractSingleton;

/**
 * Map of entity name => representing class
 * Class EntityRegistry
 * @package MoySklad\Registries
 */
class EntityRegistry extends AbstractSingleton{
    protected static $instance = null;
    public $entities = [
        AbstractEntity::class,
        AbstractDocument::class,
        PaymentIn::class,
        PaymentOut::class,
        AbstractOrder::class,
        CustomerOrder::class,
        PurchaseOrder::class,
        Assortment::class,
        Counterparty::class,
        Organization::class,
        AbstractProduct::class,
        Product::class,
        Bundle::class,
        Service::class,
        Employee::class,
        Group::class,
        Uom::class,
        Account::class,
        ContactPerson::class,
        State::class,
        PriceType::class,
        AbstractPosition::class,
        LossPosition::class,
        EnterPosition::class,
        MovePosition::class,
        CustomerOrderPosition::class,
        InventoryPosition::class,
        DemandPosition::class,
        InvoicePosition::class,
        SupplyPosition::class,
        SalesReturnPosition::class,
        PurchaseOrderPosition::class,
        AbstractComponent::class,
        BundleComponent::class,
        Country::class,
        Webhook::class,
        ProductFolder::class,
        Consignment::class,
        Variant::class,
        AbstractMovement::class,
        Enter::class,
        Move::class,
        Attribute::class,
        Publication::class,
        Store::class,
        Characteristics::class,
        CompanySettings::class,
        CustomEntity::class,
        CustomTemplate::class,
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
        SalesReturn::class,
        AbstractFacture::class,
        FactureIn::class,
        FactureOut::class,
        Inventory::class,
        RetailShift::class,
        AbstractCommissionReport::class,
        CommissionReportIn::class,
        CommissionReportOut::class,
        AbstractProcessing::class,
        Processing::class,
        ProcessingOrder::class,
        ProcessingPlan::class,
        ProcessingPlanFolder::class,
        PriceList::class,
        PriceListRow::class,
        Audit::class,
        AuditEvent::class,
        SalesChannel::class,
        ProcessingPlanMaterial::class,
        ProcessingPlanProduct::class,
        ProcessingProduct::class,
        ProcessingMaterial::class,
        Bonustransaction::class,
        Bonusprogram::class
    ];
    public $entityNames = [];

    protected function __construct()
    {
        foreach ($this->entities as $i=>$e){
            $this->entityNames[$e::$entityName] = $e;
        }
    }

    public function bootEntities(){
        foreach ($this->entities as $e){
            $e::boot();
        }
    }
}
