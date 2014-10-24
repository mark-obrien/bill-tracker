<?php
return array(
    'db' => array(
        'username'       => 'root', 
        'password'       => '123456',
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=zend;host=localhost',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter'
                    => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
);