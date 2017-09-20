<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class)
            ->add('active', ChoiceType::class, array(
                'choices'  => array(
                    'app.common.form.boolean.yes' => true,
                    'app.common.form.boolean.no' => false
                ))
            )
            ->add('credentials', ChoiceType::class, [
                'choices' => $options['credentials']->getCredentialsList(),
                "multiple" => true,
                "expanded" => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('credentials'))
            ->setDefaults(array(
                'credentials'
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'user_bundle_user_group_form_type';
    }
}
