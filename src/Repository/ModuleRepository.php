<?php

namespace App\Repository;

use App\Entity\Course;
use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository implements ModuleRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function getAllForCourse(Course $course): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.course = :course')
            ->setParameter('course', $course)
            ->orderBy('m.position', 'ASC')
            ->leftJoin('m.lessons', 'l')
            ->addOrderBy('l.position', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
