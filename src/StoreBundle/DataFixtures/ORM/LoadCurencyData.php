<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 2/17/2016
 * Time: 2:08 AM
 */

namespace StoreBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use StoreBundle\Entity\Currency;

class LoadCurrencyData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $currency = (new Currency())
            ->setName('UAH')
            ->setEnabled(true);

        $manager->persist($currency);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}