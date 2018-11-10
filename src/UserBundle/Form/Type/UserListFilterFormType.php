<?php

namespace UserBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserListFilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => false
            ])
            ->add('email', EmailType::class, [
                'required' => false
            ])
            ->add('group', EntityType::class, [
                'class' => 'UserBundle\Entity\UserGroup',
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'user_bundle_user_list_filter_form_type';
    }
}
