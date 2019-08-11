<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Lesson;
use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface LessonRepositoryInterface
{
    public function getOneById(string $id): ?LessonInterface;

    public function getByModule(ModuleInterface $module): array;

    public function getByCourse(CourseInterface $course): array;

    public function getNextInModule(LessonInterface $lesson): ?LessonInterface;

    public function getPreviousInModule(LessonInterface $lesson): ?LessonInterface;
}
