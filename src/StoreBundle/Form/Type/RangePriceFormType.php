<?php
/**
 * Created by PhpStorm.
 * User: G-SHARK
 * Date: 3/2/2016
 * Time: 12:11 AM
 */

namespace StoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RangePriceFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'StoreBundle\Model\RangePrice',
            'method' => 'get'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('minPrice', 'integer', [
                'required' => false,
                'label' => false
            ])
            ->add('maxPrice', 'integer', [
                'required' => false,
                'label' => false
            ])
        ;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'rangePrice';
    }
}