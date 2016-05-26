<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 02.02.16
 * Time: 4:19
 */

namespace StoreBundle\Admin\Entity;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use StoreBundle\Entity\Order;

class OrderAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('statusName', 'text')
            ->add('sum', 'text', ['template' => 'StoreBundle:Admin:order_sum.html.twig'])
            ->add('contacts', 'text', ['template' => 'StoreBundle:Admin:order_contact.html.twig'])
            ->add('items', 'text', ['template' => 'StoreBundle:Admin:order_items.html.twig'])
            ->add('createdAt')
        ;
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('status', null, [], 'choice', ['choices' => Order::getStatusNames()]);
    }
}