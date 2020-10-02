<?php

namespace UserBundle\Form\Type;

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
                'choices' => $options['credentialsList']->getCredentialsList(),
                "multiple" => true,
                "expanded" => true,
                "choice_attr" => array(
                    "class" => "credential_check"
                ),
                "attr" => array(
                    "class" => "credential_check"
                )
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('credentialsList'))
            ->setDefaults(array(
                'credentialsList'
            ))
        ;
    }

    public function getBlockPrefix()
    {
        return 'user_bundle_user_group_form_type';
    }
}
