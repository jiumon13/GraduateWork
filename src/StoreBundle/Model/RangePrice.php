<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 3/2/2016
 * Time: 12:32 AM
 */

namespace StoreBundle\Model;

class RangePrice
{
    /**
     * @var integer
     */
    private $minPrice;

    /**
     * @var integer
     */
    private $maxPrice;

    /**
     * @return int
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * @param int $minPrice
     * @return RangePrice
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPrice()
    {
        return $this->maxPrice;
    }

    /**
     * @param int $maxPrice
     * @return RangePrice
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }
}