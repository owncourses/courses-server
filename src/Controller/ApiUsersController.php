<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\UserCreateEvent;
use App\Form\ErrorHandler;
use App\Form\RegisterUserType;
use App\Manager\UserManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiUsersController extends AbstractController
{
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
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

    public function registerOrUpdateUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserManagerInterface $userManager,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        if (!$request->request->has('email')) {
            return new JsonResponse(['message' => 'User email is not provided'], Response::HTTP_BAD_REQUEST);
        }

        $user = $userManager->getOrCreateUser($request->request->get('email'));
        $form = $formFactory->create(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (null !== $courseName = $form->get('course')->getData()) {
                $userManager->addCourseByTitle($user, $courseName);
            }

            $newUser = false;
            if (!$entityManager->contains($user)) {
                $entityManager->persist($user);
                $newUser = true;
            }

            $entityManager->flush();
            $tokenStorage->getToken()->setUser($user);

            if ($newUser) {
                $eventDispatcher->dispatch(new UserCreateEvent($user));
            }

            return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_CREATED);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }
}
