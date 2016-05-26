<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 28.11.15
 * Time: 3:00
 */

namespace CartBundle\Manager;

use CartBundle\Entity\Cart;
use CartBundle\Entity\Item;
use CommonBundle\Exception\ValidationException;
use Doctrine\Common\Collections\ArrayCollection;
use StoreBundle\Entity\Product;
use StoreBundle\Entity\ProductService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CartManager
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param Product                          $product
     * @param ProductService[]|ArrayCollection $services
     * @param int                              $quantity
     *
     * @throws ValidationException
     */
    public function add(Product $product, $services, $quantity)
    {
        $item = new Item($product, $services, $quantity);

        $violations = $this->container->get('validator')->validate($item);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $cart = $this->get();
        $cart->addItem($item);

        $manager = $this->container->get('doctrine.orm.entity_manager');
        $manager->persist($cart);
        $manager->flush($cart);
    }

    /**
     * @return Cart
     */
    public function get()
    {
        $session = $this->container->get('request')->getSession();
        $manager = $this->container->get('doctrine.orm.entity_manager');

        if ($session->has('cart')) {
            $id = (int) $session->get('cart');
            $cart = $manager->getRepository('CartBundle:Cart')->find($id);
        } else {
            $cart = null;
        }

        if (!$cart) {
            $cart = new Cart();
            $manager->persist($cart);
            $manager->flush($cart);
            $session->set('cart', $cart->getId());
        }

        return $cart;
    }

    /**
     * Clear cart
     */
    public function clear()
    {
        $this->container->get('request')->getSession()->remove('cart');
    }
}