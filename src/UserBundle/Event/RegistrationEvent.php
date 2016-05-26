<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 05.03.16
 * Time: 0:44
 */

namespace UserBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use UserBundle\Entity\User;

class RegistrationEvent extends Event
{
    const NAME = 'user.registration';

    /**
     * @var User
     */
    private $user;

    /**
     * RegistrationEvent constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}