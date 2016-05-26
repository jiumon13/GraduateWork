<?php
/**
 * Created by PhpStorm.
 * User: inisire
 * Date: 23.11.15
 * Time: 14:22
 */

namespace StoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterFormType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'StoreBundle\Model\Filter',
            'method' => 'get'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('category', 'entity', [
//                'required' => false,
//                'class' => 'CategoryBundle\Entity\Category',
//                'label' => 'Категория'
//            ])
            ->add('SortBy', 'choice', [
                'choices' => [
                    '' => false,
                    'Популярности' => 'popularity',
                    'Цене' => 'price',
                    'Названию' => 'name',
                ],
                'label' => 'Сортировать по',
                'choices_as_values' => true,
            ])
            ->add('OrderBy', 'choice', [
                'choices' => [
                    '' => false,
                    'Убыванию' => 'DESC',
                    'Возростанию' => 'ASC',
                ],
                'label' => 'Упорядочить по',
                'choices_as_values' => true,
            ])
            ->add('rangePrice', new RangePriceFormType(), [
                'required' => false,
                'label' => 'Цена'
            ])
            ->add('name', 'text', [
                'required' => false,
                'label' => 'Наименование'
            ])
            ->add('Apply', 'submit', [
                'label' => 'Применить'
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
        return 'filter';
    }
}