<?php
  
namespace Application\Entity;
use Doctrine\ORM\EntityRepository;


/**
 * Bill Repository
 */

class BillRepository extends EntityRepository
{

    public function getActiveBills($orderBy = null, $order = null, $returnArray = false){
        $em = $this->getEntityManager();
        $repo = $em->getRepository('\Application\Entity\Bill');
        $query = $repo->createQueryBuilder('bill')
            ->where('bill.balance > 0')
            ->orderBy('bill.creditor', 'ASC');

        if($returnArray){
            return $query->getQuery()->getArrayResult();
        }
        else {
            return $query->getQuery()->getResult();
        }
    }

}
