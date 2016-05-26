<?php
/**
 * Created by PhpStorm.
 * User: iNiSiRe
 * Date: 09.01.2016
 * Time: 18:14
 */

namespace UserBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = (new User())
            ->setUsername('inisire')
            ->setPlainPassword('inthecircle')
            ->setEmail('inisire@gmail.com')
            ->addRole('ROLE_SUPER_ADMIN')
            ->setEnabled(true);

        $this->container->get('fos_user.user_manager')->updateUser($user);
    }
}