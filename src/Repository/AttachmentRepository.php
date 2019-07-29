<?php

namespace App\Repository;

use App\Entity\Attachment;
use App\Model\CourseInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Attachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attachment[]    findAll()
 * @method Attachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttachmentRepository extends ServiceEntityRepository implements AttachmentRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Attachment::class);
    }

    public function getAllForCourse(CourseInterface $course): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.lesson', 'l')
            ->leftJoin('l.module', 'm')
            ->where('m.course = :course')
            ->setParameter('course', $course)
            ->orderBy('a.name')
            ->getQuery()
            ->getResult()
            ;
    }
}
