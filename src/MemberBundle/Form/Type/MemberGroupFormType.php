<?php

namespace MemberBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use MemberBundle\Entity\MemberGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('label', TextType::class, array(
            'required' => true
        ))
            ->add('members', EntityType::class, array(
                'class' => 'MemberBundle\Entity\Member',
                'choice_label' => 'fullName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.lastName', 'ASC')
                        ->addOrderBy('m.firstName', 'ASC')
                        ;
                },
                'multiple' => true,
                'required' => true
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MemberGroup::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'member_bundle_member_group_form_type';
    }
}
