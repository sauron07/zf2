<?php
return array(
    'doctrine' => array(
        'driver' =>array(
            'user_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/User/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'User\Entity' => 'user_entities',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user[/][/:action][/:id]',
                    'constrains' => array(
                        'action' => '[a-zA-Z][a-zA-z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'index',
                    ),
                ),
            ),
            'registration' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]registration[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'registration'
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),
);