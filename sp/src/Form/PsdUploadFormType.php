<?php

namespace App\Form;

use App\Entity\PhdMessages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PsdUploadFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
        $oBuilder
            ->add('csrfup', HiddenType::class, [
            	'mapped' => false
			])
        ;
        $oBuilder->setMethod('POST');
		$options['app_service']->addPsdField($options['uploaddir'], $oBuilder, 'psdfileFileDeffer');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        /*$resolver->setDefaults([
            'data_class' => PhdMessages::class,
        ]);*/
		$resolver->setRequired('uploaddir');
		$resolver->setRequired('app_service');
    }

    public function getName(): string
	{
		return 'app.psd_up_form';
	}
}
