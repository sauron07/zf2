<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/6/14
 * Time: 12:44 PM
 */
namespace User\Controller;

use User\Entity\User;
use User\Form\Registration;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    /** @var  \User\Service\User */
    protected $service;

    public function onDispatch(MvcEvent $e){
        $this->service = $this->getServiceLocator()->get('User\Service\User');
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

    public function registrationAction(){

        /** @var \User\Form\Registration $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\Registration');
        $form->get('submit')->setValue('Registration');

        if($this->request->isPost()){
            $date = $this->request->getPost();
            $form->setData($date);

            if ($form->isValid()){
                die('success');
            }else{
                var_dump($form);die;
                die('fail');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);

    }
}