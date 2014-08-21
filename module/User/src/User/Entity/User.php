<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Crypt\Password\Bcrypt;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="User\Repository\UserRepository")
 */
class User
{
    const USER_ENTITY = 'User\Entity\User';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy = "AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(name="password_salt", length=32, type="string", nullable=true)
     */
    protected $passwordSalt;

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = self::hashPassword($this, $password, true);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param bool $generateSaltIfEmpty
     * @return mixed
     */
    public function getPasswordSalt($generateSaltIfEmpty = false)
    {
        if($generateSaltIfEmpty && empty($this->getPasswordSalt())){
            $this->setPasswordSalt(md5(uniqid()));
        }
        return $this->passwordSalt;
    }

    /**
     * @param mixed $passwordSalt
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    }

    /**
     * @param $user
     * @param $password
     * @param $generateSaltIfEmpty
     * @return string
     */
    public function hashPassword(User $user, $password, $generateSaltIfEmpty = false)
    {
        $salt = $user->getPasswordSalt($generateSaltIfEmpty);
        if(!empty($salt)){
            $bcrypt = new Bcrypt(['salt' => $salt, 'cost' => 8]);
            return $bcrypt->create($password, $salt);
        }

        return md5(md5($password));
    }
}