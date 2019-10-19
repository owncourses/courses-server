<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Course;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface CourseRepositoryInterface
{
    public function getAll(): array;

    public function getOneById(int $courseId): ?Course;

    public function getOneByTitleOrSku(string $titleOrSku): ?Course;
}
