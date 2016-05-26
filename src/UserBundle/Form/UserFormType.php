<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('email')
            ->add('plain_password', 'password', [
                'property_path' => 'plainPassword',
                'empty_data' => uniqid()
            ])
            ->add('receive_mail', 'checkbox')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'UserBundle\Entity\User',
            'validation_groups' => ['Default', 'registration']
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }
}
