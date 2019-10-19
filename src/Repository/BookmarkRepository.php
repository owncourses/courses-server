<?php

namespace App\Repository;

use App\Entity\Bookmark;
use App\Model\BookmarkInterface;
use App\Model\LessonInterface;
use App\Model\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Bookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bookmark[]    findAll()
 * @method Bookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookmarkRepository extends ServiceEntityRepository implements BookmarkRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bookmark::class);
    }

    public function getAllForLessonAndUser(LessonInterface $lesson, UserInterface $user): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.lesson = :lesson')
            ->andWhere('b.user = :user OR b.user IS NULL')
            ->setParameter('lesson', $lesson)
            ->setParameter('user', $user)
            ->orderBy('b.timeInSeconds', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getOneById(string $id): ?BookmarkInterface
    {
        return $this->createQueryBuilder('b')
        ->where('b.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }
}
