<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 22.01.16
 * Time: 18:06
 */

namespace StoreBundle\Manager;

use CommonBundle\Model\Manager;
use Doctrine\ORM\EntityManager;
use StoreBundle\Entity\Order;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class OrderManager extends Manager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @inheritdoc
     */
    public function __construct(EntityManager $manager, ContainerInterface $container)
    {
        parent::__construct($manager);
        $this->container = $container;
    }

    /**
     * @return Order
     */
    public function get()
    {
        $cart = $this->container->get('cart.manager')->get();

        return new Order($cart);
    }

    /**
     * @param Order $order
     *
     * @return bool
     */
    public function optimize(Order $order)
    {
        $user = $order->getUser();

        if (!$user) {
            return false;
        }

        $existingUser = $this->manager->getRepository('UserBundle:User')->findOneBy([
            'email' => $user->getEmail()
        ]);

        if (!$existingUser) {
            return false;
        }

        $order->setUser($existingUser);

        foreach ($existingUser->getContacts() as $contact) {
            if ($contact->getValue() == $order->getContact()) {
                $order->setContact($contact);
                break;
            }
        }

        foreach ($existingUser->getAddresses() as $address) {
            if ($address->getFull() == $order->getAddress()) {
                $order->setAddress($address);
                break;
            }
        }

        return true;
    }

    /**
     * Clear current order
     */
    public function clear()
    {
        $this->container->get('request')->getSession()->remove('order');
    }
}