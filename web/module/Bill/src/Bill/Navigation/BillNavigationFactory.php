<?php

namespace Bill\Navigation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BillNavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = new BillNavigation();
        return $navigation->createService($serviceLocator);
    }
}