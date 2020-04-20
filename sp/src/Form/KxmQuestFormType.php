<?php

namespace App\Form;

use App\Entity\KxmQuest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KxmQuestFormType extends AbstractType
{
    /** @var string $prefix = 'kxm' */
    public static $prefix = 'kxm';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id')
            ->add('body')
            ->add('var1')
            ->add('var2')
            ->add('var3')
            ->add('var4')
            ->add('var_right')
            ->add('price')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => KxmQuest::class,
        ]);
    }

    /**
     *
     * @return string Обертка вокруг полей формы
    */
    public function getBlockPrefix()
    {
        return static::$prefix;
    }
}
