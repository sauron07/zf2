<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/23/14
 * Time: 7:48 PM
 */

namespace User\Controller;

use Application\Service\Traits\EntityManagerAwareTrait;
use User\Entity\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Navigation\Page\Mvc;
use Zend\View\Model\ViewModel;
use User\Form\Login;
use User\Service\Users;

class AuthController extends AbstractActionController
{
    /** @var  Users */
    protected  $service;

    public function onDispatch(MvcEvent $e)
    {
        $this->service = $this->getServiceLocator()->get('User\Service\Users');
        parent::onDispatch($e);
    }
    /** @var  \Doctrine\ORM\EntityManager */
    protected $em;

    public function registrationAction(){

        /** @var \User\Form\Registration $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\Registration');
        $form->get('submit')->setValue('Registration');

        if($this->request->isPost()){
            $date = $this->params()->fromPost();
            $form->setData($date);

            if ($form->isValid()){
                /** @var \Doctrine\ORM\EntityManager $em */
                $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
                /** @var \User\Repository\UserRepository $userRepo */
                $userRepo = $em->getRepository('User\Entity\User');
                $user = new User();
                $userRepo->registerUser($user, $date);
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function authAction()
    {
        /** @var Login $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get('User\Form\Login');

        $message = null;
        /** @var Users $service */

        $form->getInputFilterSpecification();
        if($this->params()->fromPost()){
            $data = $this->params()->fromPost();
            $form->setData($data);

            if($form->isValid()){
                $this->service->login($data);
            }
        }
        return new ViewModel([
                'form' => $form,
                'message' => $message,
        ]);
    }

    public function logoutAction()
    {
        $this->service->logout();
        $this->redirect()->toRoute('home');
    }
}