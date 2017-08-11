<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class)
            ->add('role', TextType::class)
        ;
//            ->add('userToken', HiddenType::class)
//            ->add('password', RepeatedType::class, array(
//            'type' => PasswordType::class,
//            'invalid_message' => 'The password fields must match.',
//            'options' => array('attr' => array('class' => 'password-field')),
//            'required' => true,
//            'first_options'  => array('label' => 'Password'),
//            'second_options' => array('label' => 'Repeat Password'),
//        ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'user_bundle_user_group_form_type';
    }
}
