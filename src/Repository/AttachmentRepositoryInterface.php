<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Attachment;
use App\Model\CourseInterface;

/**
 * @method Attachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attachment[]    findAll()
 * @method Attachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface AttachmentRepositoryInterface
{
    public function getAllForCourse(CourseInterface $course): array;
}
