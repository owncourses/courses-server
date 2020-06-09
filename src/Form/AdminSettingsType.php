<?php

namespace App\Form;

use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class AdminSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('new_user_email_template', SimpleFormatterType::class, [
                'format' => 'richhtml',
                'label' => 'New user email template',
                'attr' => ['class' => 'ckeditor'],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('new_user_email_title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'New user email title',
                'constraints' => [
                    new NotNull(), new Length(['min' => 3]),
                ],
            ])
            ->add('new_course_email_template', SimpleFormatterType::class, [
                'format' => 'richhtml',
                'label' => 'New course email template',
                'attr' => ['class' => 'ckeditor'],
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('new_course_email_title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'New course email title',
                'constraints' => [
                    new NotNull(), new Length(['min' => 3]),
                ],
            ])
            ->add('password_reset_email_template', SimpleFormatterType::class, [
                'format' => 'richhtml',
                'label' => 'Password reset email template',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('password_reset_email_title', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Password reset email title',
                'constraints' => [
                    new NotNull(), new Length(['min' => 3]),
                ],
            ])
            ->add('email_from_address', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email address for sent emails',
                'constraints' => [
                    new NotNull(), new Length(['min' => 1]),
                ],
            ])
            ->add('email_from_name', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label' => 'Email name for sent emails',
                'constraints' => [
                    new NotNull(), new Length(['min' => 1]),
                ],
            ])

        ;
    }

    public function getBlockPrefix(): string
    {
        return 'settings';
    }
}
