<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 28.11.15
 * Time: 2:58
 */

namespace CartBundle\Model;

use CartBundle\Entity\Item;
use Doctrine\Common\Collections\ArrayCollection;

class Cart
{
    /**
     * @var Item[]|ArrayCollection
     */
    protected $items;

    /**
     * Cart constructor
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    /**
     * @param Item $item
     */
    public function add(Item $item)
    {
        $added = false;

        // TODO: Group items
//        foreach ($this->items as $existItem) {
//            $exists = $existItem->getProduct()->getId() == $item->getProduct()->getId();
//            if ($exists) {
//                $existItem->setQuantity($existItem->getQuantity() + $item->getQuantity());
//                $added = true;
//            }
//        }

        if (!$added) {
            $this->items->add($item);
        }
    }

    /**
     * @return Item[]|ArrayCollection
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * @return Item[]|ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[]|ArrayCollection $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }
}