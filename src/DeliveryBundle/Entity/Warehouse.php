<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 24.02.16
 * Time: 23:41
 */

namespace DeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="WarehouseRepository", )
 * @ORM\Table(name="nova_poshta_warehouses")
 */
class Warehouse
{
    const TYPE_CARGO = 1;
    const TYPE_POST = 2;
    const TYPE_MINI = 3;
    const TYPE_POSTOMAT = 4;

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
     * @ORM\Column(type="string", length=8)
     */
    private $number;

    /**
     * @var City
     *
     * @ORM\ManyToOne(targetEntity="DeliveryBundle\Entity\City", inversedBy="warehouses")
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=16)
     */
    private $maxWeight;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param City $city
     *
     * @return Warehouse
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return Warehouse
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return Warehouse
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Warehouse
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * @param string $maxWeight
     *
     * @return Warehouse
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;

        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Отделение №' . $this->getNumber();
    }
}