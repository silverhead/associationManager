<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\DashboardBundleSetting;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardBundleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle', ChoiceType::class, array(
                'choices' => $options['bundles'],
                'choice_value' => function($bundle = null){
                    //dump($bundle);
                    return $bundle!= null? $bundle->getBundleCode(): '';
                },
                'choice_label' =>  function($bundle, $key, $index){

                    return $bundle->getLabel();
                }
            ))
            ->add('group', EntityType::class, array(
                'class' => 'UserBundle\Entity\UserGroup',
                'choice_label' => 'label',
                'choice_value' => 'id'
            ))
            ->add('order', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('bundles'))
                 ->setDefaults(array(
                     'bundles',
                     'data_class' => DashboardBundleSetting::class
                 ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_dashboard_setting_form_type';
    }
}
