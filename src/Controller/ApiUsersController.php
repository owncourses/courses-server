<?php

declare(strict_types=1);

namespace App\Controller;

use App\Generator\StringGeneratorInterface;
use App\Form\RegisterUserType;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

final class ApiUsersController extends AbstractController
{
    protected $stringGenerator;

    protected $serializer;

    public function __construct(StringGeneratorInterface $stringGenerator, SerializerInterface $serializer)
    {
        $this->stringGenerator = $stringGenerator;
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

    public function registerUser(
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepository
    ): Response {
        $user = new User();
        $form = $formFactory->create(RegisterUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && $userEmail = $user->getEmail()) {
            $existingUser = $userRepository->getOneByEmail($userEmail);
            if (null !== $existingUser) {
                return new JsonResponse(['message' => 'User with provided email already exists'], Response::HTTP_CONFLICT);
            }

            $generatedPassword = $passwordEncoder->encodePassword($user, $this->stringGenerator::random(7));
            $user->setPassword($generatedPassword);
            $user->setPasswordNeedToBeChanged(true);

            $entityManager->persist($user);
            $entityManager->flush();

            return new Response($this->serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_CREATED);
        }

        return new Response($this->serializer->serialize($form, 'json'), Response::HTTP_BAD_REQUEST);
    }
}
