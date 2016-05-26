<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 14.01.16
 * Time: 19:49
 */

namespace UserBundle\Model;

use Symfony\Component\Validator\Constraints as Constraints;
use UserBundle\Entity\User;

class AuthorizationToken
{
    /**
     * @Constraints\NotBlank()
     * @Constraints\Email(checkHost=true, checkMX=true)
     *
     * @var string
     */
    private $email;

    /**
     * @Constraints\NotBlank()
     * @Constraints\Length(min="5", max="32")
     *
     * @var string
     */
    private $password;

    /**
     * @var User
     */
    private $user;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return AuthorizationToken
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return AuthorizationToken
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}