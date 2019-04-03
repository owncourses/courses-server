<?php

namespace App\Admin;

use App\Entity\Course;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CourseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', TextType::class, ['required' => false]);

        $fileFieldOptions = ['required' => false];
        if (null !== $this->getSubject()->getCoverImageName()) {
            //$imagePath = $container->get('app.uploader.storage')->resolveUri($this->getSubject(), 'coverImageFile', Course::class);
            $imagePath = $container->get('vich_uploader.templating.helper.uploader_helper')->asset($this->getSubject(), 'coverImageFile');

            $fileFieldOptions['help'] = '<img src="'.$imagePath.'" class="admin-preview" />';
        }
        $formMapper->add('coverImageFile', FileType::class, $fileFieldOptions);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->add('description');
        $listMapper->add('coverImageName');
    }
}
