<?php

namespace AccountingBundle\Form\Type;

use AccountingBundle\Repository\JournalRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountableAccountFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('journal', EntityType::class, [
                'class' => 'AccountingBundle\Entity\Journal',
                'query_builder' => function (JournalRepository $er) {
                    return $er->createQueryBuilder('j')->orderBy('j.label', 'ASC');
                },
                'choice_label' => 'label'
            ])
            ->add('code', TextType::class)
            ->add('label', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'accounting_bundle_accountableaccount_form_type';
    }
}
