<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/6/14
 * Time: 12:44 PM
 */
namespace User\Controller;

use User\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    /** @var  \User\Service\Users */
    protected $service;

    public function onDispatch(MvcEvent $e){
        $this->service = $this->getServiceLocator()->get('User\Service\Users');
        return parent::onDispatch($e);
    }

    public function indexAction(){

        /** @var \Doctrine\ORM\EntityManager $objectManager */
        $objectManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        $user = new \User\Entity\User();

        $user->setLogin('test');
        $user->setEmail('test@mail.ru');
        $user->setPassword(md5('test'));

        $objectManager->persist($user);
        $objectManager->flush();

        var_dump($user->getId()); die;
    }

    public function cabinetAction()
    {
        die('cabinet');
    }
}