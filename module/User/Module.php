<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/6/14
 * Time: 11:24 AM
 */

namespace User;

use Application\Service\EntityManagerAwareInterface;
use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $serviceManager      = $e->getApplication()->getServiceManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
    }

    public function bootstrapSession(MvcEvent $e)
    {
        $session = $e->getApplication()
            ->getServiceManager()
            ->get('Zend\Session\SessionManager');
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $session->regenerateId(true);
            $container->init = 1;
        }
    }

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
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }


    public function getServiceConfig()
    {
        return [
            'initializers' => [
                'EntityManager' => function ($instance, ServiceLocatorInterface $sm){
                        if($instance instanceof EntityManagerAwareInterface){
                            /** @var EntityManager $entityManager */
                            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
                            $instance->setEntityManager($entityManager);
                        }
                    }
            ],
            'factories' => [
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                        return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
                'Zend\Session\SessionManager' => function ($sm) {
                        $config = $sm->get('config');
                        if (isset($config['session'])) {
                            $session = $config['session'];

                            $sessionConfig = null;
                            if (isset($session['config'])) {
                                $class = isset($session['config']['class'])  ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                                $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                                $sessionConfig = new $class();
                                $sessionConfig->setOptions($options);
                            }

                            $sessionStorage = null;
                            if (isset($session['storage'])) {
                                $class = $session['storage'];
                                $sessionStorage = new $class();
                            }

                            $sessionSaveHandler = null;
                            if (isset($session['save_handler'])) {
                                // class should be fetched from service manager since it will require constructor arguments
                                $sessionSaveHandler = $sm->get($session['save_handler']);
                            }

                            $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);

                            if (isset($session['validator'])) {
                                $chain = $sessionManager->getValidatorChain();
                                foreach ($session['validator'] as $validator) {
                                    $validator = new $validator();
                                    $chain->attach('session.validate', array($validator, 'isValid'));

                                }
                            }
                        } else {
                            $sessionManager = new SessionManager();
                        }
                        Container::setDefaultManager($sessionManager);
                        return $sessionManager;
                    },
                'User\Service\Users' => function($sm){
                        return new Service\Users(
                            $sm->get('Zend\Authentication\AuthenticationService'),
                            $sm->get('Zend\Session\SessionManager')
                        );
                    }
            ],
        ];
    }
    public function getFormElementConfig()
    {
        return array(
            'invokables' => array(
                'User\Form\Registration'    => 'User\Form\Registration',
                'User\Form\Login'           => 'User\Form\Login'
            ),
        );
    }

}