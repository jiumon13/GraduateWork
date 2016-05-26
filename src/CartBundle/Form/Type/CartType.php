<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 28.11.15
 * Time: 22:08
 */

namespace CartBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CartType extends AbstractType
{
    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CartBundle\Entity\Cart',
            'csrf_protection' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', 'collection', [
                'type' => new CartItemType(),
                'allow_delete' => true,
                'by_reference' => false,
                'disabled' => $options['disabled']
            ])
            ->add('save', 'submit', ['label' => 'Сохранить'])
            ->add('checkout', 'submit', ['label' => 'Оформить заказ']);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'cart';
    }
}