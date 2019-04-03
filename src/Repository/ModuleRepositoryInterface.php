<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Module;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface ModuleRepositoryInterface
{
    public function getAllForCourse(Course $course): array;
}
