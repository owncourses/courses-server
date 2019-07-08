<?php

declare(strict_types=1);

namespace App\Manager;

use App\Factory\UserFactoryInterface;
use App\Model\UserInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\UserRepositoryInterface;

final class UserManager implements UserManagerInterface
{
    private $courseRepository;

    private $userRepository;

    private $userFactory;

    public function __construct(CourseRepositoryInterface $courseRepository, UserRepositoryInterface $userRepository, UserFactoryInterface $userFactory)
    {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    public function addCourseByTitle(UserInterface $user, string $courseTitle): void
    {
        $course = $this->courseRepository->getOneByTitle($courseTitle);
        if (null !== $course) {
            $user->addCourse($course);
        }
    }

    public function getOrCreateUser(string $email): UserInterface
    {
        $user = $this->userRepository->getOneByEmail($email);
        if (null === $user) {
            $user = $this->userFactory->create();
        }

        return $user;
    }
}
