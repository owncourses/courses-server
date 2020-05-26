<?php

declare(strict_types=1);

namespace App\Admin;

use App\Model\NotificationInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class NotificationAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('text')
            ->add('url')
            ->add('label')
            ->add('created')
            ->add('updated')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('text')
            ->add('url')
            ->add('urlTitle')
            ->add('label')
            ->add('created')
            ->add('updated')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('title')
            ->add('text')
            ->add('url')
            ->add('urlTitle')
            ->add('label', ChoiceType::class, [
                    'choices' => [
                        'New' => NotificationInterface::LABEL_NEW,
                        'Important' => NotificationInterface::LABEL_IMPORTANT,
                    ],
                    'required' => true,
                ]
            )
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('text')
            ->add('url')
            ->add('urlTitle')
            ->add('label')
            ->add('created')
            ->add('updated')
            ;
    }
}
