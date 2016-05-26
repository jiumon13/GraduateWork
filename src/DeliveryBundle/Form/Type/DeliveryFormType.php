<?php

namespace DeliveryBundle\Form\Type;

use DeliveryBundle\Entity\NovaPoshtaDelivery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DeliveryFormType constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'DeliveryBundle\Entity\NovaPoshtaDelivery',
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', 'choice', [
                'mapped' => false,
                'choices' => ['nova_poshta' => "Новая почта"]
            ])
            ->add('city', 'text')
            ->add('warehouse', 'text')
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onSubmit']);
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        /** @var NovaPoshtaDelivery $delivery */
        $delivery = $form->getData();

        $city = (int) $delivery->getCity();
        $warehouse = (int) $delivery->getWarehouse();

        $warehouse = $this->entityManager->getRepository('DeliveryBundle:Warehouse')->findOneBy([
            'id' => $warehouse
        ]);

        $city = $this->entityManager->getRepository('DeliveryBundle:City')->findOneBy([
            'id' => $city
        ]);

        if (!$city) {
            $form->get('city')->addError(new FormError('Выберите город'));
        } else {
            $delivery->setCity($city);
        }

        if (!$warehouse) {
            $form->get('warehouse')->addError(new FormError('Выберите отделение'));
        } else {
            $delivery->setWarehouse($warehouse);
        }

        if (is_object($city) && is_object($warehouse) && $warehouse->getCity()->getId() != $city->getId()) {
            $form->get('city')->addError(new FormError('Выберите город'));
            $form->get('warehouse')->addError(new FormError('Выберите отделение'));
        }
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'delivery';
    }
}