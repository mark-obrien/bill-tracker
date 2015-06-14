<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class ConsultantNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'consultant';
    }
}