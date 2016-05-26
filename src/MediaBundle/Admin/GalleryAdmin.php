<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 02.11.15
 * Time: 0:15
 */

namespace MediaBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GalleryAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('images', 'sonata_type_collection', [], [
                'edit' => 'inline',
                'inline' => 'table'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
        ;
    }
}