<?php

namespace MemberBundle\Form\Type;

use MemberBundle\Form\Model\MemberImportModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemberImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => MemberImportModel::class
        ));
    }

    public function getBlockPrefix()
    {
        return 'member_bundle_member_import_form_type';
    }
}
