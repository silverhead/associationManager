<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'first_options' => array('label' => 'user.user.edit.form.password'),
                'second_options' => array('label' => 'user.user.edit.form.password_confirmation'),
            ])
            ->add('group', EntityType::class, [
                'class' => 'UserBundle:UserGroup',
                'choice_label' => 'label'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('credentialsList'))
            ->setDefaults(array(
                'credentialsList'
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'user_bundle_user_group_form_type';
    }
}
