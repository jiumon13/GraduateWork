<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 02.11.15
 * Time: 0:15
 */

namespace MediaBundle\Admin;

use MediaBundle\Entity\Image;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ImageAdmin extends Admin
{
    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $form)
    {
        $choices = [];
        foreach (array_keys(Image::$formats) as $format) {
            $choices[$format] = $format;
        }

        $form
            ->add('file', 'file', ['required' => false])
            ->add('format', 'choice', ['choices' => $choices])
            ->add('filename', 'text', ['disabled' => true])
            ->add('alt', 'text', ['required' => false])
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('filename')
            ->add('preview', 'text', ['template' => 'MediaBundle:Admin:preview.html.twig'])
            ->add('alt')
        ;
    }
}