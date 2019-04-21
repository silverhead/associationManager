<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailSettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("emailType", EntityType::class, [
                'required' => true,
                "placeholder" => 'app.common.form.choice',
                'class' => 'AppBundle\Entity\EmailSetting',
                'choice_label' => 'title',
                'choice_value' => 'id'
            ])
            ->add('subject', TextType::class, [
                'required' => true
            ])
            ->add('body', TextareaType::class, [
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_setting_email_form_type';
    }
}
