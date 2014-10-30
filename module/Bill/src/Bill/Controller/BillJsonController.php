<?php

namespace Bill\Controller;

use Application\Controller\AbstractBillController;
use Zend\View\Model\ViewModel;
use Application\Entity\Bill;
use Bill\Form\BillForm;
use Application\Entity\Payment;
use Bill\Form\PaymentForm;
use Zend\View\Model\JsonModel;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;

class BillJsonController extends AbstractBillController
{

    public function indexAction(){
        return $this->notFoundAction();
    }

    public function paymentAction(){

        $bill_id = ($this->params()->fromRoute('id')) ? $this->params()->fromRoute('id') : 1;

        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Payment');
        $query = $repo->createQueryBuilder('c')
            ->where('c.bill = :bill_id')
            ->setParameter('bill_id', $bill_id);

        $data = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $result = new JsonModel($data);

        return $result;

    }

    public function billAction(){

        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Bill');
        $query = $repo->createQueryBuilder('c');
        $data = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $result = new JsonModel($data);

        return $result;

    }
} 