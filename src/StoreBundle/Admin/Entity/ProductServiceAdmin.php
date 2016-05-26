<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 14.10.15
 * Time: 20:05
 */

namespace StoreBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ProductServiceAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('service')
            ->add('price')
        ;
    }
}