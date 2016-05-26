<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 4/27/2016
 * Time: 2:25 PM
 */

namespace CategoryBundle\Admin\Entity;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class CategoryAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->tab('General')
            ->with('General')
            ->add('parent')
            ->add('name')
            ->add('alias', 'text', ['required' => false])
            ->add('image', 'sonata_type_model_list')
            ->add('weight')
            ->add('products')
            ->add('article')
            ->add('enabled')
            ->end()
            ->end()
            ->tab('Meta')
            ->with('Meta')
            ->add('meta', 'metadata')
            ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('name')
            ->add('alias')
            ->add('weight')
            ->add('enabled')
        ;
    }
}