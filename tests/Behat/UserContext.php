<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Course;
use App\Entity\User;
use App\Manager\UserManagerInterface;
use App\Model\UserInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserContext extends AbstractObjectContext implements Context
{
    private EntityManagerInterface $entityManager;

    private CourseRepositoryInterface $courseRepository;

    private UserRepositoryInterface $userRepository;

    private UserPasswordEncoderInterface $encoder;

    private UserManagerInterface $userManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CourseRepositoryInterface $courseRepository,
        UserRepositoryInterface $userRepository,
        UserManagerInterface $userManager,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->courseRepository = $courseRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->userManager = $userManager;
    }

    /**
     * @Given the following Users:
     */
    public function theFollowingUsers(TableNode $table)
    {
        foreach ($table as $row => $columns) {
            $user = new User();
            if (\array_key_exists('password', $columns)) {
                $columns['password'] = $this->encoder->encodePassword($user, $columns['password']);
            }

            $this->fillObject($user, $columns);
            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given that :arg1 user have :arg2 course
     */
    public function thatUserHaveCourse($userEmail, $courseTitle)
    {
        /** @var UserInterface $user */
        $user = $this->userRepository->findOneBy(['email' => $userEmail]);
        $this->userManager->addCourseByTitleOrSku($user, $courseTitle);
        $this->entityManager->flush();
    }
}
