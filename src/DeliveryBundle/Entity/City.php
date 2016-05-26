<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 24.02.16
 * Time: 23:40
 */

namespace DeliveryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CityRepository", )
 * @ORM\Table(name="nova_poshta_cities")
 */
class City
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128)
     */
    private $name;

    /**
     * @var Warehouse[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DeliveryBundle\Entity\Warehouse", mappedBy="city")
     */
    private $warehouses;

    /**
     * City constructor
     */
    public function __construct()
    {
        $this->warehouses = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     *
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Warehouse[]|ArrayCollection
     */
    public function getWarehouses()
    {
        return $this->warehouses;
    }

    /**
     * @param Warehouse $warehouse
     *
     * @return $this
     */
    public function addWarehouse(Warehouse $warehouse)
    {
        $warehouse->setCity($this);
        $this->warehouses->add($warehouse);

        return $this;
    }

    /**
     * @param Warehouse[]|ArrayCollection $warehouses
     *
     * @return City
     */
    public function setWarehouses($warehouses)
    {
        $this->warehouses = $warehouses;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}