<?php

namespace App\Repository;

use App\Entity\DemoLesson;
use App\Model\CourseInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DemoLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method DemoLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method DemoLesson[]    findAll()
 * @method DemoLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DemoLessonRepository extends ServiceEntityRepository implements DemoLessonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DemoLesson::class);
    }

    public function findAllForCourse(CourseInterface $course): array
    {
        return $this->createQueryBuilder('dl')
            ->where('m.course = :course')
            ->leftJoin('dl.lesson', 'l')
            ->leftJoin('l.module', 'm')
            ->setParameter('course', $course)
            ->getQuery()
            ->getResult();
    }
}
