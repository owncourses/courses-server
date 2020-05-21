<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\DemoLesson;
use App\Model\CourseInterface;

/**
 * @method DemoLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoLesson[]    findAll()
 * @method DemoLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface DemoLessonRepositoryInterface
{
    public function findAllForCourse(CourseInterface $course): array;
}
