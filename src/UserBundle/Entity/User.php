<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @UniqueEntity("email", message="This email is already used")
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
        /**
         * @var int
         *
         * @ORM\Id()
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\ManyToMany(targetEntity="UserBundle\Entity\Group")
         */
        protected $groups;

        /**
         * @NotBlank()
         * @Email(checkMX=true, checkHost=true)
         *
         * @var string
         */
        protected $email;

        /**
         * @var string
         *
         * @NotBlank()
         * @Length(min="2", max="64")
         */
        protected $firstname;

        /**
         * @var string
         *
         * @NotBlank()
         * @Length(min="2", max="64")
         */
        protected $lastname;

        /**
         * @var string
         *
         * @NotBlank(groups={"registration"})
         * @Regex(pattern="#^380\d{9}$#", message="Номер телефона должен быть в формате 380ХХХХХХХ", groups={"registration"})
         */
        protected $phone;

        /**
         * @var string
         *
         * @NotBlank(groups={"registration"})
         * @Length(min="3", max="128", groups={"registration"})
         */
        protected $plainPassword;

        /**
         * @var bool
         *
         * @ORM\Column(type="boolean", nullable=true)
         */
        protected $receiveMail;

        /**
         * User constructor
         */
        public function __construct()
        {
                parent::__construct();
                $this->username = uniqid();
        }

        /**
         * @return boolean
         */
        public function isReceiveMail()
        {
                return $this->receiveMail;
        }

        /**
         * @param boolean $receiveMail
         *
         * @return $this
         */
        public function setReceiveMail($receiveMail)
        {
                $this->receiveMail = $receiveMail;

                return $this;
        }
}
