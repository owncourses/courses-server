<?php

namespace App\Provider;

use App\Factory\UserLessonFactoryInterface;
use App\Model\LessonInterface;
use App\Model\UserLessonInterface;
use App\Repository\UserLessonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLessonProvider implements UserLessonProviderInterface
{
    private $userLessonRepository;
    private $security;
    private $userLessonFactory;
    private $entityManager;

    public function __construct(
        UserLessonRepositoryInterface $userLessonRepository,
        Security $security,
        UserLessonFactoryInterface $userLessonFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->userLessonRepository = $userLessonRepository;
        $this->security = $security;
        $this->userLessonFactory = $userLessonFactory;
        $this->entityManager = $entityManager;
    }

    public function getCurrentUserLesson(LessonInterface $lesson): UserLessonInterface
    {
        $user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            throw new AuthenticationException('Looks like User is not authorized.');
        }

        $userLesson = $this->userLessonRepository->getOneByUserAndLesson($user, $lesson);
        if (null === $userLesson) {
            $userLesson = $this->userLessonFactory->create($user, $lesson);
            $this->entityManager->persist($userLesson);
        }

        return $userLesson;
    }
}
