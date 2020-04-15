<?php

namespace App\Form;

use App\Entity\Pages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesListFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('url')
			->add('master')
			->add('hat')
			->add('adv')
			->add('banner')
			->add('selfadvblockIdTop')
			->add('questId')
			->add('contentBlock')
			->add('selfadvblockIdBottom')
			->add('rightColumn')
			->add('footer')
			->add('title')
			->add('heading')
			->add('menuHeading')
			->add('meta')
			->add('isMenu')
			->add('isLeftMenu')
			->add('heading2')
			->add('isDeleted')
			->add('delta')
			->add('logo')
			->add('isHidden')
			->add('description')
			->add('keywords')
			->add('createdAt')
			->add('updatedAt')
			->add('ogTitle')
			->add('ogDescription')
			->add('ogImage')
			->add('categoryId')
			->add('rating')
			->add('rightMenuSecondaryText')
			->add('hiddenInList')
			->add('forceRecompiled')
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Pages::class,
		]);
	}

	public function getName() : string
	{
		return 'articleslist';
	}
}
