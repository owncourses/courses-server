<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bookmark;
use App\Model\BookmarkInterface;
use App\Model\LessonInterface;
use App\Model\UserInterface;

/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface BookmarkRepositoryInterface
{
    public function getAllForLessonAndUser(LessonInterface $lesson, UserInterface $user): array;

    public function getOneById(string $id): ?BookmarkInterface;
}
