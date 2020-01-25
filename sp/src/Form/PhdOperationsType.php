<?php

namespace App\Form;

use App\Entity\PhdOperations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhdOperationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('userId')
            ->add('opCodeId')
            ->add('mainId')
            ->add('created')
            ->add('sum')
            ->add('payTransactionId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhdOperations::class,
        ]);
    }
}
