<?php

namespace MemberBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberEmailSettingFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('welcomeEmailSubject', TextType::class)
            ->add('welcomeEmailBody', TextareaType::class)
            ->add('newSubscriptionEmailSubject', TextType::class)
            ->add('newSubscriptionEmailBody', TextareaType::class)
            ->add('newFeeComingSoonEmailSubject', TextType::class)
            ->add('newFeeComingSoonEmailBody', TextareaType::class)
            ->add('lateMemberNotificationEmailSubject', TextType::class)
            ->add('lateMemberNotificationEmailBody', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getBlockPrefix()
    {
        return 'member_bundle_member_email_setting_form_type';
    }
}