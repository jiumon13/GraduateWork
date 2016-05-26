<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 22.11.15
 * Time: 15:41
 */

namespace StoreBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use StoreBundle\Entity\Product;
use StoreBundle\Model\Filter;

/**
 * Class ProductRepository
 *
 * @package StoreBundle\Entity\Repository
 */
class ProductRepository extends EntityRepository
{
    /**
     * @param Filter $filter
     *
     * @return Product[]
     */
    public function findByFilter(Filter $filter)
    {
        $builder = $this->createQueryBuilder('p');

        if ($filter->getCategory()) {
            $builder->join('p.categories', 'c')
                ->where('c.id = :category')
                ->setParameter('category', $filter->getCategory());
        }

        if ($filter->getName() !== null) {
            $builder->andWhere('p.name LIKE :name')
                ->setParameter('name', sprintf('%%%s%%', $filter->getName()));
        }

        if ($filter->getRangePrice() !== null) {
            $builder->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $filter->getRangePrice()->getMaxPrice() * 100);
        }

        if ($filter->getRangePrice() !== null) {
            $builder->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $filter->getRangePrice()->getMinPrice() * 100);
        }

        if ($filter->getOrderBy() && $filter->getSortBy()) {
            $builder->orderBy('p.' . $filter->getSortBy(), $filter->getOrderBy());
        }

        return $builder->getQuery()->getResult();
    }

    public function getCount($category = false)
    {
        $builder = $this->getEntityManager()->createQueryBuilder();

        if ($category) {
            $builder
                ->select('COUNT(product)')
                ->from('CategoryBundle:Category', 'category')
                ->join('category.products', 'product')
                ->where('category.id = :category')
                ->andWhere('product.enabled = true')
                ->setParameter('category', $category);
        } else {
                $builder
                    ->select('COUNT(p)')
                    ->from('StoreBundle:Product', 'p')
                    ->where('p.enabled = true');
        }

        $sql = $builder->getQuery()->getSingleScalarResult();
        return $sql;
    }

        /**
         * @param $category
         * @param $offset
         * @param $limit
         * @return Product[]
         */
    public function findByOffset($category, $limit, $offset)
    {
        $builder = $this->createQueryBuilder('p');

        $builder->join('p.categories', 'c')
            ->where('c.id = :category')
            ->setParameter('category', $category)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        return $builder->getQuery()->getResult();
    }
}