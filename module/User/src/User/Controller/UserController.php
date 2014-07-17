<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/6/14
 * Time: 12:44 PM
 */
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
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

//        var_dump($this->getRequest()); die;
//        $formView->setTemplate('user\partial\_registration');
        if($this->params()->fromPost()){
            var_dump($this->params()->fromPost()); die;
        }
//
        return new ViewModel([
            'form' => $form
        ]);

    }
}