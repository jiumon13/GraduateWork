<?php

namespace CategoryBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;
use Tools\AliasGenerator;

/**
 * Class Tag
 *
 * @Entity()
 * @Table(name="tags")
 *
 * @HasLifecycleCallbacks()
 *
 * @package Application\Category\Entity
 */
class Tag
{
    /**
     * @var int
     *
     * @Id()
     * @Column(type="integer")
     * @GeneratedValue()
     */
    private $id;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @Column(type="string")
     */
    private $alias;

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name ?: '';
    }

    /**
     * @PrePersist()
     * @PreUpdate()
     */
    public function generateAlias()
    {
        $this->alias = empty($this->alias)
            ? AliasGenerator::generate($this->name)
            : $this->alias;
    }
}
