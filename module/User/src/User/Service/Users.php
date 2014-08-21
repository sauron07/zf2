<?php
/**
 * Created by PhpStorm.
 * User: home
 * Date: 7/24/14
 * Time: 10:24 PM
 */

namespace User\Service;


use Application\Service\EntityManagerAwareInterface;
use Application\Service\Traits\EntityManagerAwareTrait;
use Doctrine\ORM\EntityManager;
use User\Entity\User;
use Zend\Authentication\Result ;
use Zend\Authentication\AuthenticationService;
use Zend\Session\SessionManager;

class Users implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /** @var AuthenticationService  */
    public $authService;


    protected $sessionManager;

    public function __construct(AuthenticationService $authService, SessionManager $sessionManager)
    {
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
    }
    public function init()
    {
        $this->repository = $this->getEntityManager()->getRepository('User\Entity\User');
    }

    public function registerUser(User $user, $date)
    {
        $this->getUserRepository()->registerUser($user, $date);
            $data = ['message' => 'User registered success', 'success' => true];
//            $data = ['message' => 'User registered fail', 'success' => false];
        return $data;
    }

    public function login($params)
    {
        $login = $params['login'];
        $pass = $params['password'];
            $adapter = $this->authService->getAdapter();
            $adapter->setIdentityValue($login);
            $adapter->setCredentialValue($pass);

            /** @var AuthenticationService $result */
            $result = $adapter->authenticate();
//            $result = $this->validateResult($result);

            $identity = $result->getIdentity();
            $this->authService->getStorage()->write($identity);

            // TODO: Сделать отдельный trait для authService

            if($params['rememberMe']){
                $time = 1209600;
                $this->sessionManager->rememberMe($time);
            }


        return $message;
    }
    public function logout()
    {
        $this->authService->clearIdentity();
        $this->sessionManager->forgetMe();
    }


    /**
     * @param $result
     * @throws \Exception
     * @return mixed
     */
    private function validateResult($result)
    {
        $message = null;
        if(!$result->isValid()){
            switch ($result->getCode()){
                case (Result::FAILURE_CREDENTIAL_INVALID):
                    $message = 'Неверный пароль';
                    break;
                case (Result::FAILURE_IDENTITY_NOT_FOUND):
                    $message = 'Пользователь не найден';
                    break;
                default:
                    $message = 'Ошибка авторизации';
            }
            throw new \Exception($message);
        }
        return $result;
    }

    /**
     * @return \User\Repository\UserRepository
     */
    private function getUserRepository()
    {
        return $this->getEntityManager()->getRepository(User::USER_ENTITY);
    }
}