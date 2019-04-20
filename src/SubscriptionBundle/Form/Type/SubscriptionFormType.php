<?php

namespace SubscriptionBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class)
            ->add('cost', NumberType::class)
            ->add('duration', IntegerType::class)
            ->add('code', TextType::class)
            ->add('periodicities', EntityType::class, [
                'class' => 'SubscriptionBundle\Entity\SubscriptionPaymentPeriodicity',
                'choice_label' => 'label',
                'multiple' => true
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'app.common.status.enabled' => true,
                    'app.common.status.disabled' => false,
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'subpscription_bundle_subpscription_subpscription_form_type';
    }
}
