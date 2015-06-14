<?php
/**
 * Created by PhpStorm.
 * User: Tom
 * Date: 11/5/2014
 * Time: 1:35 PM
 */

namespace Application\Entity\Listeners;


use Zend\ServiceManager\ServiceManager;

class ServiceManagerListener
{
    protected $sm;

    public function __construct(ServiceManager $sm)
    {
        $this->sm = $sm;
    }

    public function postLoad($eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $class = new \ReflectionClass($entity);
        if($class->implementsInterface('Zend\ServiceManager\ServiceLocatorAwareInterface'))
        {
            $entity->setServiceLocator($this->sm);
        }
    }
} 