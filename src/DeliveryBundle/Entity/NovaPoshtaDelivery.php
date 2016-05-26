<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 26.02.16
 * Time: 18:44
 */

namespace DeliveryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @ORM\Entity()
 */
class NovaPoshtaDelivery extends Delivery
{
    /**
     * @var City
     *
     * @Constraints\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="DeliveryBundle\Entity\City")
     */
    private $city;

    /**
     * @var Warehouse
     *
     * @Constraints\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="DeliveryBundle\Entity\Warehouse")
     */
    private $warehouse;

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
     * @return NovaPoshtaDelivery
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Warehouse
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * @param Warehouse $warehouse
     *
     * @return NovaPoshtaDelivery
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Новая почта, %s, %s, Отделение №%s',
            $this->city->getName(),
            $this->warehouse->getAddress(),
            $this->warehouse->getNumber()
        );
    }
}