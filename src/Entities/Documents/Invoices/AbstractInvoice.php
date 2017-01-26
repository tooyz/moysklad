<?php

namespace MoySklad\Entities\Documents\Invoices;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Traits\CreatesWithOrgAgentPositions;

class AbstractInvoice extends AbstractDocument{
    use CreatesWithOrgAgentPositions;
    public static $entityName = 'a_invoice';
}