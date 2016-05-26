<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 18.02.16
 * Time: 22:31
 */

namespace ContentBundle\Extension;

use CommonBundle\Entity\MetaData;
use ContentBundle\Entity\Page;
use Doctrine\ORM\EntityManager;

class ContentExtension extends \Twig_Extension
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * MetaTagExtension constructor
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('page', [$this, 'getPage'])
        ];
    }

    /**
     * @param string $url
     *
     * @return Page
     */
    public function getPage($url)
    {
        return $this->manager->getRepository('ContentBundle:Page')->findOneBy([
            'url' => $url,
            'enabled' => true
        ]);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'context_extension';
    }
}