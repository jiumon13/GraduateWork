<?php
/**
 * Created by PhpStorm.
 * User: iNiSiRe
 * Date: 25.01.2016
 * Time: 21:45
 */

namespace StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="products_has_services")
 */
class ProductService
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
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="StoreBundle\Entity\Product", inversedBy="services", cascade={"persist"})
     */
    private $product;

    /**
     * @var Service
     *
     * @ORM\ManyToOne(targetEntity="StoreBundle\Entity\Service")
     */
    private $service;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="StoreBundle\Entity\Currency")
     */
    private $currency;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     *
     * @return ProductService
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param Service $service
     *
     * @return ProductService
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return round($this->price / 100, 2);
    }

    /**
     * @param int $price
     *
     * @return ProductService
     */
    public function setPrice($price)
    {
        $this->price = round($price * 100);

        return $this;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return ProductService
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->service->getName();
    }
}
