<?php

namespace StoreBundle\Form;

use StoreBundle\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderFormType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'StoreBundle\Entity\Order',
            'validation_groups' => ['Default', 'checkout']
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name', 'text', ['property_path' => 'firstName'])
            ->add('last_name', 'text', ['property_path' => 'lastName'])
            ->add('phone')
            ->add('delivery', 'delivery');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'order';
    }
}
