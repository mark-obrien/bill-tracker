<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\User;

class IndexController extends AbstractActionController
{
    public function indexAction() {
	    $objectManager = $this
	        ->getServiceLocator()
	        ->get('Doctrine\ORM\EntityManager');

	    $user = new \Application\Entity\User();
	    $user->setFullName('Marco Pivetta');

	    $objectManager->persist($user);
	    $objectManager->flush();

    die(var_dump($user->getId())); // yes, I'm lazy
	}
}
