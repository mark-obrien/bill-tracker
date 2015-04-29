<?php

namespace Bill;


return array(
    'controllers' => array(
        'invokables' => array(
            'Bill\Controller\Bill' => 'Bill\Controller\BillController',
            'Bill\Controller\BillJson' => 'Bill\Controller\BillJsonController',
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
            'bill-json' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/bill/json[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Bill\Controller\BillJson',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'bill' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Home',
                'route' => 'home',
            ),
            array(
                'label' => 'Page #1',
                'route' => 'bill',
                'pages' => array(
                    array(
                        'label' => 'Child #1',
                        'route' => 'bill'
                    )
                )
            ),
            array(
                'label' => 'Page #2',
                'route' => 'bill',
            )
        )
    ),
    'service_manager' => array(
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
    ),
);