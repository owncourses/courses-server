<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;

final class AttachmentAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $formMapper->add('name');
        $fileFieldOptions = ['required' => false];
        if (null !== $this->getSubject()->getFileName()) {
            $filePath = $container->get('vich_uploader.templating.helper.uploader_helper')->asset($this->getSubject(), 'file');

            $fileFieldOptions['help'] = 'Current file: '.$filePath;
        }
        $formMapper->add('file', FileType::class, $fileFieldOptions);
        $formMapper->add('lesson');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
        $datagridMapper->add('lesson.title');
        $datagridMapper->add('lesson.module.course');
        $datagridMapper->add('fileName');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->add('lesson.title');
        $listMapper->add('fileName', 'string', ['template' => 'admin/attachment/download.html.twig']);
    }
}
