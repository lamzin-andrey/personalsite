<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhdAdminUploaderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
        /*$builder
            ->add('field_name')
        ;*/
		$oBuilder->setMethod('POST');
		//*
		$options['app_service']->addZipFileField($options['uploaddir'], $oBuilder, 'resultfileFileImmediately');
		$options['app_service']->addBigImageFileField($options['uploaddir'], $oBuilder, 'previewfileFileImmediately');
		$options['app_service']->addBigImageFileField($options['uploaddir'], $oBuilder, 'previewnoticefileFileImmediately');
		$options['app_service']->addZipFileField($options['uploaddir'], $oBuilder, 'htmlexampleFileImmediately');
		$options['app_service']->addBigImageFileField($options['uploaddir'], $oBuilder, 'previewcssFileImmediately');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*$resolver->setDefaults([
            // Configure your form options here
        ]);*/
		$resolver->setRequired('uploaddir');
		$resolver->setRequired('app_service');
    }

    /**
     *
     * @param $
     * @return
    */
    public function getName()
    {
    	return 'app.preview_up_form';
    }
}
