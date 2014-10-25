<?php

namespace Bill;

use Application\Form\View\Helper\FormRowBS;

return array(
    'controllers' => array(
        'invokables' => array(
            'Bill\Controller\Bill' => 'Bill\Controller\BillController',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
              'drivers' => array(
                'Application\Entity' => 'application_entities'
              )
            )
        )
    ),
    'router' => array(
        'routes' => array(
            'bill' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/bill[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Bill\Controller\Bill',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
//    'view_helpers' => array(
//        'invokables' => array(
//            'formrow' => 'Application\Form\View\Helper\FormRowBS'
//        ),
//    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'bill' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);