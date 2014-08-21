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
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'User\Entity\User',
                'identity_property' => 'login',
                'credential_property' => 'password',
                'credential_callable' => 'User\Entity\User::hashPassword',
            ),
        ),
    ),
    'session' => array(
        'config' => array(
            'class' => 'Zend\Session\Config\SessionConfig',
            'options' => array(
                'name' => 'myapp',
            ),
        ),
        'storage' => 'Zend\Session\Storage\SessionArrayStorage',
        'validators' => array(
            array(
                'Zend\Session\Validator\RemoteAddr',
                'Zend\Session\Validator\HttpUserAgent',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
            'User\Controller\Auth' => 'User\Controller\AuthController',
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
                        'controller' => 'User\Controller\Auth',
                        'action' => 'registration'
                    ),
                ),
            ),
            'auth' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]auth[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action' => 'auth'
                    ),
                ),
            ),
            'logout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '[/]logout[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\Auth',
                        'action' => 'logout'
                    ),
                ),
            ),
            'cabinet' => [
                'type' => 'segment',
                'options' => [
                    'route' => '[/]cabinet[/]',
                    'defaults' => [
                        'controller' => 'User\Controller\User',
                        'action' => 'cabinet'
                    ]
                ]
            ]
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
        'display_exceptions' => true,
    ),
);