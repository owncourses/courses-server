<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class AuthorAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $formMapper->add('name', TextType::class);
        $formMapper->add('bio', TextType::class);
        $formMapper->add('courses');
        $fileFieldOptions = ['required' => false];
        if (null !== $this->getSubject()->getPicture()) {
            $imagePath = $container->get('vich_uploader.templating.helper.uploader_helper')->asset($this->getSubject(), 'pictureFile');

            $fileFieldOptions['help'] = '<img src="'.$imagePath.'" class="admin-preview" />';
        }
        $formMapper->add('pictureFile', FileType::class, $fileFieldOptions);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name');
        $listMapper->add('bio');
        $listMapper->add('picture');
        $listMapper->add('courses');
        $listMapper->add('created');
        $listMapper->add('updated');
    }
}
