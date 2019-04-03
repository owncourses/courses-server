<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\UserInterface;
use App\Form\RegisterUserType;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;

final class ApiUsersController extends AbstractController
{
    public function me(SerializerInterface $serializer): Response
    {
        return new Response($serializer->serialize($this->getUser(), 'json', [
            'groups' => [
                'user_details',
                'course_details',
            ],
        ]));
    }

    public function registerUser(
        SerializerInterface $serializer,
        Request $request,
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ): Response {
        $user = new User();
        $form = $formFactory->create(RegisterUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $existingUser = $userRepository->getOneByEmail($user->getEmail());
                if (null !== $existingUser) {
                    return new JsonResponse(['message' => 'User with provided email already exists'], Response::HTTP_CONFLICT);
                }

                $generatedPassword = $passwordEncoder->encodePassword($user, $this->randomStr(7));
                $user->setPassword($generatedPassword);
                $user->setPasswordNeedToBeChanged(true);

                $entityManager->persist($user);
                $entityManager->flush();

                $eventDispatcher->dispatch(UserInterface::EVENT_USER_CREATED, new GenericEvent($user));

                return new Response($serializer->serialize($user, 'json', ['groups' => ['user_details']]), Response::HTTP_CREATED);
            }

            return new Response($serializer->serialize($form, 'json'), Response::HTTP_BAD_REQUEST);
        }

        return new Response($serializer->serialize($form, 'json'), Response::HTTP_BAD_REQUEST);
    }

    private function randomStr(int $length): string
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[\random_int(0, $max)];
        }

        return $str;
    }
}
