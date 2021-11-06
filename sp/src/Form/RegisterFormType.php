<?php

namespace App\Form;

use App\Entity\Ausers;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType AS TType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TType::class)
            ->add('surname', TType::class)
            ->add('email', EmailType::class)
            ->add('username', TType::class)
            ->add('passwordRaw', PasswordType::class, [
            	'mapped' => false,
				'constraints' => [
					new NotBlank(),
					new Length([
						'min' => 6,
						'max' => 128
					]),
					new Regex([
						'pattern' => "/(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])/s",
						'message' => 'Password must containts symbols in upper and lower case and numbers'
					])
				]
			])
			->add('passwordRepeat', PasswordType::class, [
				'mapped' => false
			])
			->add('agree', CheckboxType::class, [
				'mapped' => false,
				'required' => true,
				'constraints' => [
					new EqualTo([
					    'value' => 'true',
						'message' => 'Consent to agree to terms of use {{ value }}'
					])
				]
			])
			->add('isSubscribed', CheckboxType::class, [
				'required' => false
			])
        ;
    }

    public function getName() : string
	{
    	return 'regform';
	}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ausers::class,
        ]);
    }
}
