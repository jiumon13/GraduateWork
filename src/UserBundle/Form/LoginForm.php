<?php

namespace UserBundle\Form;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use UserBundle\Model\AuthorizationToken;

class LoginForm extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var EncoderFactory
     */
    private $encoderFactory;

    /**
     * LoginForm constructor.
     *
     * @param EntityManager $manager
     */
    public function __construct(EntityManager $manager, EncoderFactory $encoderFactory)
    {
        $this->manager = $manager;
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'UserBundle\Model\AuthorizationToken'
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email')
            ->add('password', 'password');

        $builder->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onSubmit']);
    }

    /**
     * @param FormEvent $event
     */
    public function onSubmit(FormEvent $event)
    {
        /** @var AuthorizationToken $token */
        $token = $event->getData();

        $form = $event->getForm();

        if (!$form->get('email')->isValid()) {
            return;
        }

        $user = $this->manager->getRepository('UserBundle:User')->findOneBy([
            'email' => $token->getEmail()
        ]);

        if (!$user) {
            $event->getForm()->get('email')->addError(new FormError('Неправильный электронный адрес или пароль'));
            $event->getForm()->get('password')->addError(new FormError('Неправильный электронный адрес или пароль'));
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        if (!$encoder->isPasswordValid($user->getPassword(), $token->getPassword(), $user->getSalt())) {
            $event->getForm()->get('email')->addError(new FormError('Неправильный электронный адрес или пароль'));
            $event->getForm()->get('password')->addError(new FormError('Неправильный электронный адрес или пароль'));
            return;
        }

        $token->setUser($user);
    }

    public function getName()
    {
        return 'login';
    }
}
