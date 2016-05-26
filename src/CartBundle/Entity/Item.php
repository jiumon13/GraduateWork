<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 03.12.15
 * Time: 14:48
 */

namespace CartBundle\Entity;

use CartBundle\Model\Item as ItemModel;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use StoreBundle\Entity\Product;
use StoreBundle\Entity\ProductService;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @ORM\Entity()
 * @ORM\Table(name="cart_items")
 */
class Item extends ItemModel
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var Cart
     *
     * @ORM\ManyToOne(targetEntity="CartBundle\Entity\Cart", inversedBy="items")
     */
    protected $cart;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @var int
     *
     * @Constraints\Range(min="1")
     *
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="StoreBundle\Entity\Product")
     */
    protected $product;

    /**
     * @var ProductService[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="StoreBundle\Entity\ProductService")
     */
    protected $services;

    /**
     * @var int
     *
     * @Constraints\Range(min="1", max="1000")
     *
     * @ORM\Column(type="integer")
     */
    protected $quantity;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set cart
     *
     * @param Cart $cart
     *
     * @return Item
     */
    public function setCart(Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @param bool $withServices
     *
     * @return string
     */
    public function getName($withServices = false)
    {
        $name = $this->name;

        if ($withServices && $this->getServices()->count() > 0) {
            $name = sprintf('%s (%s)', $name, implode(', ', $this->getServicesNames()));
        }

        return $name;
    }

    /**
     * @return array
     */
    public function getServicesNames()
    {
        $names = [];
        foreach ($this->services as $service) {
            $names[] = $service->getName();
        }

        return $names;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Item
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @param bool $withServices
     *
     * @return float
     */
    public function getPrice($withServices = false)
    {
        $price = round($this->price / 100, 2);

        if ($withServices) {
            foreach ($this->services as $service) {
                $price += $service->getPrice();
            }
        }

        return $price;
    }

    /**
     * Add service
     *
     * @param ProductService $service
     *
     * @return Item
     */
    public function addService(ProductService $service)
    {
        $this->services[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param ProductService $service
     */
    public function removeService(ProductService $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return ProductService[]|Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Item
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }
}
