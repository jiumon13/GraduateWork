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

class ProductAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('General')
                ->with('General')
                    ->add('name')
                    ->add('alias', 'text', ['required' => false])
                    ->add('description', 'sonata_formatter_type', array(
                        'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                        'format_field' => 'descriptionFormat',
                        'format_field_options' => ['choices' => ['richhtml' => 'richhtml'], 'mapped' => false],
                        'source_field_options' => ['attr' => ['class' => 'span10', 'rows' => 10]],
                        'source_field' => 'description',
                        'target_field' => 'description',
                        'ckeditor_context' => 'default'
                    ))
                    ->add('price', 'number', ['precision' => 2])
                    ->add('currency')
                    ->add('tags')
                    ->add('categories', null, [
                        'by_reference' => false
                    ])
                    ->add('gallery', 'sonata_type_model_list')
                    ->add('attributes', 'sonata_type_collection',
                        ['by_reference' => false],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'allow_delete' => true
                        ]
                    )
                    ->add('services', 'sonata_type_collection',
                        ['by_reference' => false],
                        [
                            'edit' => 'inline',
                            'inline' => 'table',
                            'allow_delete' => true
                        ]
                    )
                    ->add('popularity')
                    ->add('comment', null, ['required' => false])
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

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('price');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('name')
            ->add('price')
            ->add('enabled');
    }
}