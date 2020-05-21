<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class DemoLessonAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'created',
    ];

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('lesson');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('lesson.module.course');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('lesson.title');
        $listMapper->add('lesson.module.course');
    }
}
