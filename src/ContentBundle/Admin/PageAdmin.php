<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 03.02.16
 * Time: 4:56
 */

namespace ContentBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PageAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->tab('General')
                ->with('General')
                    ->add('url')
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
            ->addIdentifier('id')
            ->add('url')
            ->add('enabled')
        ;
    }
}