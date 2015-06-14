<?php

namespace Application\Navigation\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class FranchisorNavigationFactory extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'franchisor';
    }
}