<?php

namespace MoySklad\Entities\Documents\Factures;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Interfaces\DoesNotSupportMutationInterface;

class AbstractFacture extends AbstractDocument implements DoesNotSupportMutationInterface {
    public static $entityName = 'a_facture';
}