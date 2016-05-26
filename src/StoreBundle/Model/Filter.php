<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 23.11.15
 * Time: 14:18
 */

namespace StoreBundle\Model;

use CategoryBundle\Entity\Category;

class Filter
{
    /**
     * @var Category
     */
    private $category;

    /**
     * @var string
     */
    private $name;

    /**
     * @var RangePrice
     */
    private $rangePrice;

    /**
     * @var string
     */
    private $sortBy;

    /**
     * @var string
     */
    private $orderBy;


    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
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
     * @return mixed
     */
    public function getRangePrice()
    {
        return $this->rangePrice;
    }

    /**
     * @param mixed $rangePrice
     * @return Filter
     */
    public function setRangePrice($rangePrice)
    {
        $this->rangePrice = $rangePrice;
        return $this;
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sortBy;
    }

    /**
     * @param string $sortBy
     * @return Filter
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrderBy()
    {
        return $this->orderBy;
    }

    /**
     * @param string $orderBy
     * @return Filter
     */
    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }
}