<?php

namespace MoySklad\Entities\Documents\Factures;

use MoySklad\Entities\Documents\AbstractDocument;
use MoySklad\Interfaces\PreventsMutation;

class AbstractFacture extends AbstractDocument implements PreventsMutation {
    public static $entityName = 'a_facture';
}