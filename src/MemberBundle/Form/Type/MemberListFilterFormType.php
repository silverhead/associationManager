<?php

namespace MemberBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberListFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastName', TextType::class, [
                'required' => false
            ])
            ->add('firstName', TextType::class, [
                'required' => false
            ])
            ->add('status', EntityType::class, [
                'class' => 'MemberBundle\Entity\MemberStatus',
                'choice_value' => 'id',
                'choice_label' => 'label',
                'required' => false
            ])
            ->add('subscription', EntityType::class, [
                'class' => 'SubscriptionBundle\Entity\Subscription',
                'choice_value' => 'id',
                'choice_label' => 'label',
                'required' => false
            ])
            ->add('active', ChoiceType::class,[
                'choices' => [
                    'app.common.form.boolean.yes' => true,
                    'app.common.form.boolean.no' => false
                ],
                'required' => false
            ])
            ->add('onlyLatePaymentMember', CheckboxType::class,[
                'required' => false
            ])
            ->add('onlyNewFeeComingSoon', CheckboxType::class,[
                    'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'member_bundle_member_list_filter_form_type';
    }
}
