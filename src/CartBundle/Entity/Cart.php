<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 03.12.15
 * Time: 14:48
 */

namespace CartBundle\Entity;

use CartBundle\Model\Cart as CartModel;
use CommonBundle\Entity\TimestampableEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Constraints;

/**
 * @ORM\Entity()
 * @ORM\Table(name="carts")
 * @ORM\HasLifecycleCallbacks()
 */
class Cart extends CartModel
{
    use TimestampableEntityTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @Constraints\Count(min="0", max="50")
     * @Constraints\Valid()
     *
     * @var Item[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="CartBundle\Entity\Item", mappedBy="cart", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $items;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Cart constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->items = new ArrayCollection();
    }

    /**
     * Add item
     *
     * @param Item $item
     *
     * @return Cart
     */
    public function addItem(Item $item)
    {
        $item->setCart($this);
        $this->items->add($item);

        return $this;
    }

    /**
     * Remove item
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * @return float
     */
    public function getSum()
    {
        $sum = 0;
        foreach ($this->items as $item) {
            $sum += $item->getPrice(true) * $item->getQuantity();
        }

        return $sum;
    }
}
