<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserAdmin extends AbstractAdmin
{
    private $encoder;

    public function preUpdate($object)
    {
        parent::preUpdate($object);

        if (null === ($plainPassword = $object->getPlainPassword())) {
            return;
        }

        $encoded = $this->encoder->encodePassword($object, $plainPassword);
        $object->setPassword($encoded);
    }

    public function setEncoder(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('roles')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('courses')
            ->add('roles')
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
        $container = $this->getConfigurationPool()->getContainer();
        $roles = $container->getParameter('security.role_hierarchy.roles');

        $formMapper
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('courses')
            ->add('plainPassword', PasswordType::class, ['label' => 'Password', 'required' => false])
            ->add('roles', ChoiceType::class, array(
                    'choices' => self::flattenRoles($roles),
                    'multiple' => true,
                )
            )
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('firstName')
            ->add('lastName')
            ->add('courses')
            ->add('email')
            ->add('roles')
            ;
    }

    protected static function flattenRoles(array $rolesHierarchy): array
    {
        $flatRoles = [];
        foreach ($rolesHierarchy as $roles) {
            if (empty($roles)) {
                continue;
            }

            foreach ($roles as $role) {
                if (!isset($flatRoles[$role])) {
                    $flatRoles[$role] = $role;
                }
            }
        }

        return $flatRoles;
    }
}
