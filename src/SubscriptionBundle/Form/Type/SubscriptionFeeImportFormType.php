<?php

namespace SubscriptionBundle\Form\Type;

use SubscriptionBundle\Form\Model\SubscriptionFeeImportModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionFeeImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SubscriptionFeeImportModel::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'subscription_bundle_subscription_fee_import_form_type';
    }
}