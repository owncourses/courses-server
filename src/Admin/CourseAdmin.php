<?php

namespace App\Admin;

use App\Model\CourseInterface;
use App\Model\LessonInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class CourseAdmin extends AbstractAdmin
{
    private UploaderHelper $uploaderHelper;

    public function setUploaderHelper(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', TextType::class, ['required' => false]);
        $formMapper->add('sku', TextType::class, ['required' => false]);
        $formMapper->add('type', ChoiceType::class, [
                'choices' => [
                    'Standard' => CourseInterface::COURSE_TYPE_STANDARD,
                    'Demo' => CourseInterface::COURSE_TYPE_DEMO,
                ],
                'required' => true,
            ]
        );
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
            $imagePath = $this->uploaderHelper->asset($this->getSubject(), 'coverImageFile');

            $fileFieldOptions['help'] = '<img src="'.$imagePath.'" class="admin-preview" />';
        }
        $formMapper->add('coverImageFile', FileType::class, $fileFieldOptions);

        $formMapper->add('purchaseUrl');
        $formMapper->add('parent');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('sku');
        $datagridMapper->add('authors');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->add('description');
        $listMapper->add('sku');
        $listMapper->add('authors');
        $listMapper->add('coverImageName');
        $listMapper->add('visible');
        $listMapper->add('startDate');
        $listMapper->add('endDate');
    }
}
