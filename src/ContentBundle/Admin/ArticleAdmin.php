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

class ArticleAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('title')
            ->add('alias', null, ['required' => false])
            ->add('content', 'sonata_formatter_type', array(
                'event_dispatcher' => $form->getFormBuilder()->getEventDispatcher(),
                'format_field' => 'contentFormat',
                'format_field_options' => ['choices' => ['richhtml' => 'richhtml'], 'mapped' => false],
                'source_field_options' => ['attr' => ['class' => 'span10', 'rows' => 10]],
                'source_field' => 'content',
                'target_field' => 'content',
                'ckeditor_context' => 'default'
            ))
            ->add('enabled')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('title')
            ->add('alias')
            ->add('enabled')
        ;
    }
}