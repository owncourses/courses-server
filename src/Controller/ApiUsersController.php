<?php

declare(strict_types=1);

namespace App\Controller;

use App\Event\UserCreateEvent;
use App\Event\UserPasswordChangeRequestEvent;
use App\Form\ErrorHandler;
use App\Form\RegisterUserType;
use App\Form\UserEmailType;
use App\Form\UserPasswordResetRequestType;
use App\Form\UserPasswordResetType;
use App\Manager\UserManagerInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use SWP\Component\Common\Exception\NotFoundHttpException;
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
    protected SerializerInterface $serializer;

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
            if (null !== $courseTitleOrSku = $form->get('course')->getData()) {
                $userManager->addCourseByTitleOrSku($user, $courseTitleOrSku);
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

    public function requestPasswordReset(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserManagerInterface $userManager,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $form = $formFactory->create(UserPasswordResetRequestType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getOneByEmail($form->getData()['email']);
            if (null === $user) {
                throw new NotFoundHttpException('User was not found');
            }
            $userManager->setGeneratedPasswordResetToken($user);
            $entityManager->flush();

            $eventDispatcher->dispatch(new UserPasswordChangeRequestEvent($user));

            return new JsonResponse(['success' => true]);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }

    public function requestPassword(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserManagerInterface $userManager,
        UserRepositoryInterface $userRepository
    ): Response {
        $form = $formFactory->create(UserPasswordResetType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getOneByPasswordResetToken($request->query->get('token'));
            if (null === $user) {
                throw new NotFoundHttpException('User was not found');
            }

            $data = $form->getData();
            if ($data['password'] !== $data['repeatedPassword']) {
                throw new \InvalidArgumentException('Passwords are not equal');
            }

            $userManager->resetPassword($user, $data['password']);
            $entityManager->flush();

            return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_OK);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }

    public function checkEmail(
        Request $request,
        FormFactoryInterface $formFactory,
        UserRepositoryInterface $userRepository
    ): Response {
        $form = $formFactory->create(UserEmailType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->getOneByEmail($form->getData()['email']);

            if (null !== $user) {
                return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_OK);
            }

            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }
}
