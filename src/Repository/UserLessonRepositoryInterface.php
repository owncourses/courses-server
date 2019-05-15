<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserLesson;
use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Model\UserLessonInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLesson[]    findAll()
 * @method UserLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface UserLessonRepositoryInterface
{
    public function getOneByUserAndLesson(?UserInterface $user, LessonInterface $lesson): ?UserLessonInterface;

    public function getCompletedByUserInModule(UserInterface $user, ModuleInterface $module): array;

    public function getCompletedByUserInCourse(UserInterface $user, CourseInterface $course): array;
}
