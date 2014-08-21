<?php
namespace User\Repository;

use Doctrine\ORM\EntityRepository;
use User\Entity\User;

class UserRepository extends EntityRepository
{
    public function isAuth()
    {
        return true;
    }

    public function registerUser(User $user, $formData)
    {
        $user->setLogin($formData['login']);
        $user->setPassword($formData['password']);
        $user->setEmail($formData['email']);

        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}