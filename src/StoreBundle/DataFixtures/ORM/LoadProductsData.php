<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 2/17/2016
 * Time: 12:25 AM
 */

namespace StoreBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use StoreBundle\Entity\Product;

class LoadProductsData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $currency = $manager->getRepository('StoreBundle:Currency')->findOneBy(['name' => 'UAH']);
        $category = $manager->getRepository('CategoryBundle:Category')->findOneBy(['name' => 'Arduino']);

        $qty = 15;
        for ($i = 0; $i <= $qty; $i++) {
            $product = (new Product())
                ->setName('ArduinoBoard #' . $i)
                ->setPrice(rand(1, 1000))
                ->addCategory($category)
                ->setCurrency($currency)
                ->setEnabled(true)
                ->setPopularity(rand(0, 500));
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}