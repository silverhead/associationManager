<?php

namespace MemberBundle\Form\Type;

use MemberBundle\Entity\Member;
use MemberBundle\Entity\MemberStatus;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Form\Type\UserFormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MemberFormType extends UserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
        ->add('avatar', FileType::class, array(
            'required' => false,
            'attr' => [
                'data-current' => $options['currentAvatar']
            ]
            ))
        ->add('birthday', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('gender', ChoiceType::class, array(
            'choices' => array(
                'member.member.edit.form.gender.female' => 'f',
                'member.member.edit.form.gender.male' => 'm',
            )
        ))
        ->add('country', CountryType::class, array('preferred_choices' => array('FR')))
        ->add('city', TextType::class)
        ->add('zipcode', TextType::class)
        ->add('address', TextType::class)
        
        ->add('phone', TextType::class)
        ->add('cellular', TextType::class)
        ->add('status', EntityType::class, array(
            'class' => 'MemberBundle\Entity\MemberStatus',
            'data' => $options['entity']->getStatus()->count() > 0 ? $options['entity']->getStatus()->first()->getStatus() : '',
            'query_builder' => function (EntityRepository $er) {
                                    return $er->createQueryBuilder('ms')->orderBy('ms.label', 'ASC');
                               },
                'choice_label' => 'label'
             ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'currentAvatar',
            'entity'
        ))
        ->setDefaults(array(
            'data_class' => Member::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_member_status_form_type';
    }
}