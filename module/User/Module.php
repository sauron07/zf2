<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/6/14
 * Time: 11:24 AM
 */

namespace User;

use Zend\Form\FormElementManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
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
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getFormElementConfig()
    {
        return array(
            'invokables' => array(
                'User\Form\Registration' => 'User\Form\Registration',
            ),
        );
    }

}