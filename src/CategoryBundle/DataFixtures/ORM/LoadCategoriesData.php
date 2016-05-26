<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 2/17/2016
 * Time: 12:51 AM
 */

namespace CategoryBundle\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use CategoryBundle\Entity\Category;

class LoadCategoriesData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $category = (new Category())
            ->setName('Arduino')
            ->setDescription('Arduino Category')
            ->setEnabled(true);

        $manager->persist($category);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}