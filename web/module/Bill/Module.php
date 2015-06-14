<?php

namespace Bill;

use Bill\Model\BillTable;
use Bill\Model\paymentTable;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Bill\Model\BillTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new BillTable($dbAdapter);
                    return $table;
                },
                'Bill\Model\PaymentTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new PaymentTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }    

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}