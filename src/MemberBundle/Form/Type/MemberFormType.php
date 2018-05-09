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
use Vich\UploaderBundle\Form\Type\VichFileType;

class MemberFormType extends UserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
        ->add('avatarFile', VichFileType::class, [
            'required' => false,
            'allow_delete' => false,
            'download_uri' => false,
            'download_label' => '...',
            'attr' => [
                'data-current' => $options['currentAvatar']
            ]
        ])
        ->add('birthday', DateType::class, array(
            'widget' => 'single_text'
        ))
        ->add('gender', ChoiceType::class, array(
            'choices' => $options['gender'],
            'choice_label' => function($gender){
                return $gender;
            },
            'choice_value' => function($gender){
                return $gender;
            }
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
             ))
        ->add('expertise', ChoiceType::class, array(
           'multiple' => true,
           'choices' => $options['expertise'],
           'choice_label' => function($expertise){
               return $expertise;
            },
            'choice_value' => function($expertise){
                return $expertise;
            }
        ))
            ->add('study', ChoiceType::class, array(
                'choices' => $options['study'],
                'choice_label' => function($study){
                    return $study;
                },
                'choice_value' => function($study){
                    return $study;
                }
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'currentAvatar',
            'entity',
            'gender',
            'study',
            'expertise'
        ))
        ->setDefaults(array(
            'data_class' => Member::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_member_edit_form_type';
    }
}