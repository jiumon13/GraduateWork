<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 28.11.15
 * Time: 3:01
 */

namespace CartBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use StoreBundle\Entity\Product;
use StoreBundle\Entity\ProductService;

class Item
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var string
     */
    protected $price;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ProductService[]|ArrayCollection
     */
    protected $services;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @param Product                          $product
     * @param ProductService[]|ArrayCollection $services
     * @param int                              $quantity
     */
    public function __construct(Product $product, ArrayCollection $services, $quantity)
    {
        $this->product = $product;
        $this->name = $product->getName();
        $this->price = $product->getPriceISO();
        $this->services = $services;
        $this->quantity = $quantity;
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
     * @return Item
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }
}