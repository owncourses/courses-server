<?php

namespace App\Repository;

use App\Entity\UserLesson;
use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Model\UserLessonInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method UserLesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserLesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserLesson[]    findAll()
 * @method UserLesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserLessonRepository extends ServiceEntityRepository implements UserLessonRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserLesson::class);
    }

    public function getOneByUserAndLesson(?UserInterface $user, LessonInterface $lesson): ?UserLessonInterface
    {
        if (null === $user) {
            return null;
        }

        return $this->createQueryBuilder('ul')
            ->where('ul.lesson = :lesson')
            ->andWhere('ul.user = :user')
            ->setParameters(['user' => $user, 'lesson' => $lesson])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getCompletedByUserInModule(UserInterface $user, ModuleInterface $module): array
    {
        return $this->createQueryBuilder('ul')
            ->leftJoin('ul.lesson', 'l')
            ->andWhere('ul.user = :user')
            ->andWhere('l.module = :module')
            ->andWhere('ul.completed = true')
            ->setParameters(['user' => $user, 'module' => $module])
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCompletedByUserInCourse(UserInterface $user, CourseInterface $course): array
    {
        return $this->createQueryBuilder('ul')
            ->leftJoin('ul.lesson', 'l')
            ->leftJoin('l.module', 'm')
            ->andWhere('ul.user = :user')
            ->andWhere('m.course = :course')
            ->andWhere('ul.completed = true')
            ->setParameters(['user' => $user, 'course' => $course])
            ->getQuery()
            ->getResult()
            ;
    }
}
