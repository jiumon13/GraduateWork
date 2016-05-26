<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 4/14/2016
 * Time: 6:22 PM
 */
namespace MenuBundle\Extension;

use Doctrine\ORM\EntityManager;

class MenuTwigExtension extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('menu', [$this, 'menu'])
        ];
    }

    /**
     * @return \MenuBundle\Entity\MenuItem[]
     */
    public function menu()
    {
        return $this->entityManager->getRepository('MenuBundle:MenuItem')->findBy(['enabled' => true]);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'menu_twig_extension';
    }
}