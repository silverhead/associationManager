<?php

namespace MemberBundle\Form\Type;

use Doctrine\DBAL\Types\DecimalType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MemberSubscriptionHistoricalFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('subscription', EntityType::class, array(
            'class' => 'SubscriptionBundle:Subscription',
            'choice_attr' =>  function($choice, $index, $value){
                    $periodicitiesId = $choice->getPeriodicities()->map(function($en){
                        return $en->getId();
                    });

                    return array(
                        'data-cost' => $choice->getCost(),
                        'data-periodicities' => join(",", $periodicitiesId->getValues()),
                        'data-duration' => $choice->getDuration()
                    );
                },
            'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                            ->orderBy('s.label', 'ASC');
                },
            'choice_label' => 'label'
        ))
        ->add('subscriptionPaymentPeriodicity', EntityType::class, array(
            'class' => 'SubscriptionBundle:SubscriptionPaymentPeriodicity',
            'query_builder' => function (EntityRepository $er) {
            return $er->createQueryBuilder('p')
                        ->orderBy('p.label', 'ASC');
            },
            'choice_label' => 'label'
        ))
        ->add('startDate', DateType::class, array(
            'data' => new \DateTime(),
            'widget' => 'single_text'
        ))
        ->add('endDate', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('cost', NumberType::class, array(
        ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        
    }
    
    public function getBlockPrefix()
    {
        return 'member_member_subscription_form_type';
    }
}