<?php
/**
 * Created by PhpStorm.
 * User: iNiSiRe
 * Date: 17.02.2016
 * Time: 1:09
 */

namespace CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MetadataFormType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CommonBundle\Entity\MetaData'
        ]);

        parent::setDefaultOptions($resolver);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('keywords')
        ;
    }

    public function getName()
    {
        return 'metadata';
    }
}