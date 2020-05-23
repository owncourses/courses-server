<?php

namespace App\Admin;

use App\Model\LessonInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\FormatterBundle\Form\Type\SimpleFormatterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\AdminBundle\Route\RouteCollection;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class LessonAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'ASC',
        '_sort_by' => 'position',
    ];

    private UploaderHelper $uploaderHelper;

    public function setUploaderHelper(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('title', TextType::class);
        $formMapper->add('description', SimpleFormatterType::class, [
            'format' => 'richhtml',
            'attr' => ['class' => 'ckeditor'],
        ]);
        $formMapper->add('durationInMinutes');
        $formMapper->add('embedType', ChoiceType::class, [
                'choices' => [
                    'code' => LessonInterface::EMBED_TYPE_CODE,
                    'Vimeo' => LessonInterface::EMBED_TYPE_VIMEO,
                ],
                'required' => true,
            ]
        );
        $formMapper->add('embedCode');
        $formMapper->add('module');

        $fileFieldOptions = ['required' => false];
        if (
            null !== $this->getSubject() &&
            null !== $this->getSubject()->getCoverImageName()
        ) {
            $imagePath = $this->uploaderHelper->asset($this->getSubject(), 'coverImageFile');

            $fileFieldOptions['help'] = '<img src="'.$imagePath.'" class="admin-preview" />';
        }
        $formMapper->add('coverImageFile', FileType::class, $fileFieldOptions);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
        $datagridMapper->add('module');
        $datagridMapper->add('module.course');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
        $listMapper->add('description');
        $listMapper->add('durationInMinutes');
        $listMapper->add('module.course');
        $listMapper->add('position');
        $listMapper->add('coverImageName');
        $listMapper->add('_action', null, [
            'actions' => [
                'move' => [
                    'template' => '@PixSortableBehavior/Default/_sort_drag_drop.html.twig',
                    'enable_top_bottom_buttons' => true,
                ],
            ],
        ]);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('move', $this->getRouterIdParameter().'/move/{position}');
    }
}
