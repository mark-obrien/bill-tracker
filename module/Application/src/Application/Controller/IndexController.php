<?php

namespace Application\Controller;

use Application\Controller\AbstractBillController;
use Application\Form\LoginForm;
use Zend\View\Model\ViewModel;
use Application\Entity\User;

class IndexController extends AbstractBillController
{
//    public function indexAction() {
//	    $objectManager = $this
//	        ->getServiceLocator()
//	        ->get('Doctrine\ORM\EntityManager');
//
//	    $user = new \Application\Entity\User();
//	    $user->setFullName('Marco Pivetta');
//
//	    $objectManager->persist($user);
//	    $objectManager->flush();
//
//    die(var_dump($user->getId())); // yes, I'm lazy
//	}

    public function indexAction()
    {

        $form = new LoginForm();
        if(!empty($_COOKIE['bill-tracker']))
        {
            $data = unserialize($_COOKIE['bill-tracker']);
            if(!empty($data['username']))
            {
                $form->setData(array('username' => $data['username']));
            }
        }
        return array('form' => $form);
    }

}
