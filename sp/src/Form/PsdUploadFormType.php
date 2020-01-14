<?php

namespace App\Form;

use App\Entity\PhdMessages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PsdUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
        $oBuilder
            ->add('createdAt')
            ->add('resultLink')
            ->add('psdLink')
            ->add('serviceNotes')
            ->add('previewLink')
            ->add('isPayed')
            ->add('operationId')
            ->add('operatorId')
            ->add('isClosed')
            ->add('state')
            ->add('uid')
            ->add('isPublish')
        ;
		$options['app_service']->addPsdField($options['uploaddir'], $oBuilder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhdMessages::class,
        ]);
		$resolver->setRequired('uploaddir');
		$resolver->setRequired('app_service');
    }
}
