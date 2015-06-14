<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Application\Entity\Listeners\ServiceManagerListener;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Doctrine\ORM\Events;
use Application\Entity\EntityEventListener;
use Application\View\Helper\BillTracker;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
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

        $settings = $e->getApplication()->getServiceManager()->get('config');

        // Add Custom View Helpers (Widgets)
        $sm = $e->getApplication()->getServiceManager();

        $em->getEventManager()->addEventListener(
            array(Events::postLoad), new ServiceManagerListener($sm)
        );

        // Add Custom View Helpers (Widgets)
        $sm->get('viewhelpermanager')->setFactory('BillTracker',
        function ($sm) use ($em) {
            return new BillTracker($em, $sm);
        });

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
