<?php

namespace EmailBundle\Form\Type;

use AppBundle\Form\FormType\EditorType;
use EmailSystemBundle\Entity\EmailSystem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmailFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('subject', TextType::class)
        ->add('bodyHtml', TextareaType::class)
            ->add('bodyText', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
        ->setDefaults(array(
            'data_class' => EmailSystem::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'email_bundle_email_form_type';
    }
}