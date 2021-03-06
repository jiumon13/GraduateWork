<?php

namespace UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContactRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ContactRepository extends EntityRepository
{
    /**
     * @param Contact[] $contacts
     *
     * @return Contact[]
     */
    public function findDuplicates(array $contacts)
    {
        $values = [];
        foreach ($contacts as $contact) {
            $values[] = $contact->getValue();
        }

        return $this->createQueryBuilder('c')
            ->where('c.value IN (:values)')
            ->setParameter('values', $values)
            ->getQuery()
            ->getResult();
    }
}
