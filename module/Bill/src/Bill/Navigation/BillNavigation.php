<?php

namespace Bill\Navigation;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Application\Entity\Bill;

class BillNavigation extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        $navigation = array();

        $bills = $this->getEntityManager()->getRepository('Application\Entity\Bill')->findAll();

        if (null === $this->pages) {
            $navigation[] = array (
                'label' => 'Jaapsblog.nl',
                'uri'   => 'http://www.jaapsblog.nl'
            );

            $mvcEvent = $serviceLocator->get('Application')
                ->getMvcEvent();

            $routeMatch = $mvcEvent->getRouteMatch();
            $router     = $mvcEvent->getRouter();
            $pages      = $this->getPagesFromConfig($navigation);

            $this->pages = $this->injectComponents(
                $pages,
                $routeMatch,
                $router
            );
        }

        return $this->pages;
    }
}