<?php

namespace App\Form;

use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AdminSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_user_email_template', SimpleFormatterType::class, [
                'format' => 'richhtml',
                'attr' => ['class' => 'ckeditor'],
            ])
            ->add('new_user_email_title', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email_from_address', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])
            ->add('email_from_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
            ])

        ;
    }

    public function getBlockPrefix(): string
    {
        return 'settings';
    }
}
