<?php

namespace MemberBundle\Form\Type;

use MemberBundle\Entity\Member;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberProfileFormType extends MemberFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->remove('status');
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
        return 'member_bundle_member_profile_form_type';
    }
}