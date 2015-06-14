<?php

namespace Application\View\Helper;

use Doctrine\ORM\Query\Expr;

class BillTracker extends Widget {

    public function ListBills(){

        return $this->getRepo('bill')->getActiveBills(null, null, true);

    }

}