<?php

namespace SubscriptionBundle\Form\Type;

use SubscriptionBundle\Form\Model\SubscriptionFeeListFilterModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionFeeListFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subscription', EntityType::class, [
                'class' => 'SubscriptionBundle\Entity\Subscription',
                'choice_value' => 'id',
                'choice_label' => 'label',
                'required' => false
            ])
            ->add('fullNameMember', TextType::class, [
                'required' => false
            ])
            ->add('startDate', DateType::class,[
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('endDate', DateType::class,[
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('paid', ChoiceType::class,[
                'choices' => [
                    'app.common.form.boolean.yes' => true,
                    'app.common.form.boolean.no' => false
                ],
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getBlockPrefix()
    {
        return 'subscription_bundle_subscription_fee_list_filter_form_type';
    }
}
