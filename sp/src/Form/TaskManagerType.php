<?php

namespace App\Form;

use App\Entity\CrnTasks;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskManagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('codename')
            ->add('isPublic')
            ->add('id')
			->add('tags', TextType::class, [
				'mapped' => false
			])
			->add('parentIdData', TextType::class, [
				'mapped' => false
			])
			->add('actionType', TextType::class, [
				'mapped' => false
			])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CrnTasks::class,
        ]);
    }

    public function getName()
	{
    	return 'crn_tasks_form';
	}
}
