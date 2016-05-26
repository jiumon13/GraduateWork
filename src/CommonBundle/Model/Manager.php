<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 10.10.15
 * Time: 20:26
 */

namespace CommonBundle\Model;

use Doctrine\ORM\EntityManager;

abstract class Manager
{
    /**
     * @var EntityManager
     */
    protected $manager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->manager = $entityManager;
    }

    /**
     * @param mixed $entity
     * @param bool  $flush
     *
     * @return bool
     */
    public function save($entity, $flush = true)
    {
        $this->manager->persist($entity);
        if ($flush) {
            $this->manager->flush();
        }

        return true;
    }
}