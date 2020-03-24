<?php

namespace App\Form;

use App\Entity\PhdMessages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhdMessagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('resultLink')
            ->add('psdLink')
            ->add('serviceNotes')
            ->add('previewLink')
            ->add('noticePreviewLink')
            ->add('cssPreviewLink')
            ->add('htmlExampleLink')
            ->add('isPayed')
            ->add('operationId')
            ->add('operatorId')
            ->add('isClosed')
            ->add('state')
            ->add('uid')
            ->add('is_email_user')
            ->add('isPublish')
            ->add('phdUser')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PhdMessages::class,
        ]);
    }
}
