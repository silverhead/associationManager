<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingAppFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('logo', TextType::class, [])
            ->add('associationName', TextType::class)
            ->add('contactEmail', EmailType::class, [
                'constraints' => new EmailType()
            ])
            ->add('robotEmail', EmailType::class, [
                'constraints' => new EmailType()
            ])
            ->add('phone', TextType::class, [])
            ->add('country', CountryType::class, [])
            ->add('city', TextType::class, [])
            ->add('zipcode', TextType::class, [])
            ->add('address', TextType::class, [])
            ->add('description', TextareaType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_setting_app_form_type';
    }
}