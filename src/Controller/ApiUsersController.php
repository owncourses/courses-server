<?php

declare(strict_types=1);

namespace App\Controller;

use App\Factory\UserFactoryInterface;
use App\Form\ErrorHandler;
use App\Form\RegisterUserType;
use App\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiUsersController extends AbstractController
{
    protected $serializer;

    private $userFactory;

    public function __construct(SerializerInterface $serializer, UserFactoryInterface $userFactory)
    {
        $this->serializer = $serializer;
        $this->userFactory = $userFactory;
    }

    public function getCurrentUser(): Response
    {
        return new Response($this->serializer->serialize($this->getUser(), 'json', [
            'groups' => [
                'user_details',
                'course_details',
            ],
        ]));
    }

    public function registerUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        EventDispatcher $dispatcher
    ): Response {
        $user = $this->userFactory->create();
        $form = $formFactory->create(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $dispatcher->dispatch(new GenericEvent($user), UserInterface::EVENT_USER_CREATED);

            //TODO:
            // Catch eventand send email with link to password reset to new user
            // Add controller for handling user password change
            // Implement user password change in app
            // Add env variable for student app url (user will be send there from email)

            return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_CREATED);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }
}
