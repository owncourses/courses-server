<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository implements CourseRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function getAll(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.visible = true')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getOneById(int $courseId): ?Course
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :courseId')
            ->andWhere('c.visible = true')
            ->setParameter('courseId', $courseId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getOneByTitleOrSku(string $titleOrSku): ?Course
    {
        $qb = $this->createQueryBuilder('c');

        return $qb->where(
                $qb->expr()->orX('c.title = :titleOrSku', 'c.sku = :titleOrSku')
            )
            ->andWhere('c.visible = true')
            ->setParameter('titleOrSku', $titleOrSku)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
