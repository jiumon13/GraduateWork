<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 10.10.15
 * Time: 20:26
 */

namespace StoreBundle\Model;

use CommonBundle\Model\Manager;
use Doctrine\ORM\EntityManager;

/**
 * Class ProductManager
 *
 * @package StoreBundle\Model
 */
class ProductManager extends Manager
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;


    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);
        $this->repository = $this->manager->getRepository('StoreBundle:Product');
    }
}