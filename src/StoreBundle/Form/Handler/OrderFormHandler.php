<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 22.01.16
 * Time: 17:27
 */

namespace StoreBundle\Form\Handler;

use CartBundle\Manager\CartManager;
use Doctrine\ORM\EntityManager;
use StoreBundle\Entity\Order;
use StoreBundle\Manager\OrderManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use UserBundle\Entity\Address;
use UserBundle\Entity\Contact;
use UserBundle\Entity\User;

class OrderFormHandler
{
    /**
     * @var OrderManager
     */
    private $orderManager;

    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var CartManager
     */
    private $cartManager;

    /**
     * @var string
     */
    private $secret;

    /**
     * OrderFormHandler constructor.
     *
     * @param OrderManager  $orderManager
     * @param EntityManager $manager
     * @param CartManager   $cartManager
     * @param string        $secret
     */
    public function __construct(OrderManager $orderManager, EntityManager $manager, CartManager $cartManager, $secret)
    {
        $this->orderManager = $orderManager;
        $this->manager = $manager;
        $this->cartManager = $cartManager;
        $this->secret = $secret;
    }

    /**
     * @param Form $form
     *
     * @return Order
     *
     * @throws \Exception
     */
    public function handle(Form $form)
    {
        $data = $form->getData();

        $address = (new Address())
            ->setCountry(Address::COUNTRY_UKRAINE)
            ->setCity($data['city'])
            ->setStreet($data['street'])
            ->setBuilding($data['building'])
            ->setApartment($data['apartment']);

        $contacts[Contact::TYPE_EMAIL] = (new Contact())
            ->setType(Contact::TYPE_EMAIL)
            ->setValue($data['email']);

        $contacts[Contact::TYPE_PHONE] = (new Contact())
            ->setType(Contact::TYPE_PHONE)
            ->setValue($data['phone']);

//        $user = (new User())
//            ->setUsername($data['name'])
//            ->setEmail($data['email'])
//            ->setPhone($data['phone'])
//            ->setPassword(md5(uniqid() . $this->secret))
//            ->addAddress($address);

        $cart = $this->cartManager->get();

//        $order = (new Order($cart))
//            ->setName($data['name'])
//            ->setEmail($data['email'])
//            ->setContact($contacts[Contact::TYPE_PHONE])
//            ->setAddress($address);
//
//        $this->orderManager->optimize($order);

//        $this->manager->persist($order);
//        $this->manager->flush($order);

        $this->cartManager->clear();

//        return $order;
    }
}