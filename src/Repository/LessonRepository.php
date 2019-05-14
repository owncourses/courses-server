<?php

namespace App\Repository;

use App\Entity\Lesson;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository implements LessonRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function getOneById(string $id): ?LessonInterface
    {
        return $this->createQueryBuilder('l')
            ->where('l.id = :id')
            ->leftJoin('l.module', 'm')
            ->leftJoin('m.course', 'c')
            ->andWhere('c.visible != false')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getByModule(ModuleInterface $module): array
    {
        return $this->createQueryBuilder('l')
            ->where('l.module = :module')
            ->leftJoin('l.module', 'm')
            ->leftJoin('m.course', 'c')
            ->andWhere('c.visible != false')
            ->setParameter('module', $module)
            ->getQuery()
            ->getResult()
            ;
    }
}
