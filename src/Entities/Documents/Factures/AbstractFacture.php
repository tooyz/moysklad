<?php

namespace MoySklad\Entities\Documents\Factures;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Interfaces\DoesNotSupportMutation;

class AbstractFacture extends AbstractDocument implements DoesNotSupportMutation {
    public static $entityName = 'a_facture';
}