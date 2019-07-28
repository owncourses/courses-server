<?php

declare(strict_types=1);

namespace App\Manager;

use App\Factory\UserFactoryInterface;
use App\Generator\StringGenerator;
use App\Model\UserInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserManager implements UserManagerInterface
{
    private $courseRepository;

    private $userRepository;

    private $userFactory;

    private $passwordEncoder;

    public function __construct(CourseRepositoryInterface $courseRepository, UserRepositoryInterface $userRepository, UserFactoryInterface $userFactory, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
        $this->passwordEncoder = $passwordEncoder;
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

    public function setGeneratedPasswordResetToken(UserInterface $user): void
    {
        $user->setPasswordNeedToBeChanged(true);
        $user->setPasswordResetToken(StringGenerator::random(22));
    }

    public function resetPassword(UserInterface $user, string $password): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setPasswordNeedToBeChanged(false);
        $user->setPasswordResetToken(null);
    }
}
