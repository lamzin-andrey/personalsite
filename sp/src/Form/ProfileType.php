<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $oBuilder, array $options)
    {
        $oBuilder
            ->add('name')
            ->add('surname')
			->add('currentPassword', PasswordType::class, [
				'mapped' => false,
				'required' => false
			])
			->add('newPassword', PasswordType::class, [
				'mapped' => false,
				'required' => false
			])
			->add('repeatPassword', PasswordType::class, [
				'mapped' => false,
				'required' => false
			])
        ;
		$options['app_service']->addUserLogoFileField($options['uploaddir'], $oBuilder, 'useravatar');
		$oBuilder->setMethod('POST');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
		$resolver->setRequired('uploaddir');
		$resolver->setRequired('app_service');
    }
}
