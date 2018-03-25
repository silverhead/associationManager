<?php

namespace MemberBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MemberSubscriptionFeeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('cost', NumberType::class, array(
        ))
        ->add('payment', EntityType::class, array(
            'class' => "SubscriptionBundle\Entity\SubscriptionPaymentType",
            'choice_label' => 'label'
        ))
        ->add('paid', ChoiceType::class, array(
            'choices' => array(
                'app.common.form.boolean.yes' => true,
                'app.common.form.boolean.no' => false
            )
        ))
        ->add('paymentDate', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('startDate', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('endDate', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('note', TextareaType::class, array(
        ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }
    
    public function getBlockPrefix()
    {
        return 'member_member_subscription_fee_form_type';
    }
}