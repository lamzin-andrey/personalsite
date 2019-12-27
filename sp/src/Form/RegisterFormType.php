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
            	'mapped' => false
			])
			->add('passwordRepeat', PasswordType::class, [
				'mapped' => false
			])
			->add('agree', CheckboxType::class, [
				'mapped' => false
			])
			->add('isSubscribed', CheckboxType::class)
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
