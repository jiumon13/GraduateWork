<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 26.02.16
 * Time: 16:40
 */

namespace DeliveryBundle\Plugin\NovaPoshta\Model;

use DeliveryBundle\Entity\Warehouse;
use Symfony\Component\Validator\Constraints as Constraint;

class Delivery extends \DeliveryBundle\Entity\Delivery
{
    /**
     * @var Warehouse
     *
     * @Constraint\NotBlank()
     */
    private $warehouse;

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
     * @return Delivery
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function generatePayload()
    {
        $warehouse = $this->getWarehouse();

        $this->setPayload([
            'text' => sprintf(
                '%s, Отделение № %s, %s',
                $warehouse->getCity()->getName(),
                $warehouse->getNumber(),
                $warehouse->getAddress()
            ),
            'class' => Warehouse::class,
            'id' => $warehouse->getId()
        ]);
    }
}