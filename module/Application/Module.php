<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Events;
use Application\Entity\EntityEventListener;

class Module
{
    public function onBootstrap($e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $em = $e->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
        $em->getEventManager()->addEventListener(
            array(Events::prePersist, Events::preUpdate),
            new EntityEventListener());


        $viewModel = $e->getViewModel();

        // Load View Model variables - Current user and Site settings
        $currentUser = $em->getRepository('Application\Entity\User')->getCurrentUser();
        $viewModel->setVariables(array('currentUser' => $currentUser));

        // Load role into layout
        $currentUserRole = null;
        if($currentUser)
        {
            $currentUserRole = $currentUser->role->name;
        }
        else
        {
            $currentUserRole = null;
        }
        $viewModel->setVariables(array('currentUserRole' => $currentUserRole));



    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
