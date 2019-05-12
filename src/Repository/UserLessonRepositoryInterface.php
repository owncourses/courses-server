<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserLesson;
use App\Model\LessonInterface;
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
}
