<?php

namespace App\Admin;

use App\Entity\Author;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

final class AuthorAdmin extends AbstractAdmin
{
    private UploaderHelper $uploaderHelper;

    public function setUploaderHelper(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name', TextType::class);
        $formMapper->add('bio', TextType::class);
        $formMapper->add('gender', ChoiceType::class, [
                'choices' => [
                    'Male' => Author::AUTHOR_GENDER_MALE,
                    'Female' => Author::AUTHOR_GENDER_FEMALE,
                    'Other' => Author::AUTHOR_GENDER_OTHER,
                ],
                'required' => true,
            ]
        );
        $formMapper->add('courses');
        $fileFieldOptions = ['required' => false];
        if (null !== $this->getSubject()->getPicture()) {
            $imagePath = $this->uploaderHelper->asset($this->getSubject(), 'pictureFile');

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
