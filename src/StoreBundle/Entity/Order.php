<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 25.07.15
 * Time: 21:50
 */

namespace StoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Constraints;
use Doctrine\ORM\Mapping as ORM;
use CommonBundle\Entity\TimestampableEntityTrait;
use DeliveryBundle\Entity\Delivery;
use CartBundle\Entity\Cart;
use UserBundle\Entity\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    use TimestampableEntityTrait;

    const STATUS_CREATED = 0;
    const STATUS_NEW = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_CANCELED = 4;

    protected static $statuses = [
        self::STATUS_CREATED    => 'Created',
        self::STATUS_NEW        => 'New',
        self::STATUS_PROCESSING => 'Processing',
        self::STATUS_SUCCESS    => 'Success',
        self::STATUS_CANCELED   => 'Canceled',
    ];

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @Constraints\Valid()
     * @Constraints\NotBlank(groups={"checkout"})
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * @var string
     *
     * @Constraints\NotBlank(groups={"checkout"})
     * @ORM\Column(type="string", length=64)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Constraints\NotBlank(groups={"checkout"})
     * @ORM\Column(type="string", length=64)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Constraints\NotBlank(groups={"checkout"})
     * @Constraints\Regex(pattern="#^380\d{9}$#", message="Номер телефона должен быть в формате 380ХХХХХХХ",
     *                                            groups={"checkout"})
     *
     * @ORM\Column(type="string", length=32)
     */
    private $phone;

    /**
     * @var Cart
     *
     * @Constraints\NotBlank(groups={"checkout"})
     *
     * @ORM\OneToOne(targetEntity="CartBundle\Entity\Cart")
     */
    private $cart;

    /**
     * @var Delivery
     *
     * @Constraints\Valid()
     * @Constraints\NotBlank(groups={"checkout"})
     *
     * @ORM\OneToOne(targetEntity="DeliveryBundle\Entity\Delivery", cascade={"persist"})
     */
    private $delivery;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint")
     */
    private $status = self::STATUS_CREATED;

    /**
     * Order constructor.
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->setCart($cart);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Order
     */
    public function setUser($user)
    {
        $this->user = $user;

        if ($user) {
            $this->setFirstName($user->getFirstname())
                ->setLastName($user->getLastname())
                ->setPhone($user->getPhone());
        }

        return $this;
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * @param Cart $cart
     *
     * @return Order
     */
    public function setCart($cart)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Order
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusName()
    {
        return self::$statuses[$this->status];
    }

    /**
     * @return array
     */
    public static function getStatusNames()
    {
        return self::$statuses;
    }

    /**
     * @return Delivery
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * @param Delivery $delivery
     *
     * @return $this
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return Order
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return Order
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Order
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }
}
