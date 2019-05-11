<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class CourseAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()->getContainer();
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', TextType::class, ['required' => false]);
        $formMapper->add('visible', null, ['required' => false]);
        $formMapper->add('startDate', DateTimeType::class, [
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'required' => false,
            'html5' => true,
            'input' => 'datetime',
            'attr' => [
                'style' => 'width: 200px',
            ],
        ]);
        $formMapper->add('endDate', DateTimeType::class, [
            'widget' => 'single_text',
            'format' => DateTimeType::HTML5_FORMAT,
            'required' => false,
            'html5' => true,
            'input' => 'datetime',
            'attr' => [
                'style' => 'width: 200px',
            ],
        ]);

        $fileFieldOptions = ['required' => false];
        if (null !== $this->getSubject()->getCoverImageName()) {
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
        $listMapper->add('visible');
        $listMapper->add('startDate');
        $listMapper->add('endDate');
    }
}
