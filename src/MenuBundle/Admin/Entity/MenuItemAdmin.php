<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 4/17/2016
 * Time: 2:50 AM
 */

namespace MenuBundle\Admin\Entity;


use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class MenuItemAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('url')
            ->add('icon', null, ['required' => false])
            ->add('enabled', null, ['required' => false])
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('url')
            ->add('enabled')
        ;
    }
}