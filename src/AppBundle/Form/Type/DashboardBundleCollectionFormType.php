<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DashboardBundleCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundles', CollectionType::class, array(
                'entry_type' => DashboardBundleFormType::class,
                'allow_add' => true,
                'prototype' => true,
                'entry_options' => array(
                    'bundles' => $options['bundles']
                )
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('bundles'))
            ->setDefaults(array(
                'bundles'
            ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_dashboard_bundles_collection_form_type';
    }
}
