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
 * @ORM\Table(name="deliveries")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="service", type="string", length=32)
 * @ORM\DiscriminatorMap({"nova_poshta" = "NovaPoshtaDelivery"})
 */
abstract class Delivery
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $payload;

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
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     *
     * @return $this
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
}