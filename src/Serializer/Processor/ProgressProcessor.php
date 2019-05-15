<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Model\UserLessonInterface;
use App\Repository\LessonRepositoryInterface;
use App\Repository\UserLessonRepositoryInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProgressProcessor
{
    private $userLessonRepository;
    private $lessonRepository;
    private $security;

    public function __construct(
        UserLessonRepositoryInterface $userLessonRepository,
        LessonRepositoryInterface $lessonRepository,
        Security $security
    ) {
        $this->userLessonRepository = $userLessonRepository;
        $this->lessonRepository = $lessonRepository;
        $this->security = $security;
    }

    public function process(object $object, array $data): array
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return $data;
        }

        $userLessons = $this->getUserLessons($user, $object);
        $lessons = $this->getLessons($object);

        $completedTime = 0;
        /** @var UserLessonInterface $userLesson */
        foreach ($userLessons as $userLesson) {
            $completedTime += $userLesson->getLesson()->getDurationInMinutes();
        }

        $totalDuration = 0;
        /** @var LessonInterface $lesson */
        foreach ($lessons as $lesson) {
            $totalDuration += $lesson->getDurationInMinutes();
        }

        $data['progress'] = [
            'completed_lessons' => count($userLessons),
            'completed_percentage' => (count($userLessons) > 0 && count($lessons) > 0) ? (int) round((count($userLessons) / count($lessons)) * 100) : 0,
            'completed_time' => $completedTime,
            'total_duration' => $totalDuration,
        ];

        return $data;
    }

    private function getUserLessons(UserInterface $user, object $object): array
    {
        if ($object instanceof ModuleInterface) {
            return $this->userLessonRepository->getCompletedByUserInModule($user, $object);
        }

        if ($object instanceof CourseInterface) {
            return $this->userLessonRepository->getCompletedByUserInCourse($user, $object);
        }

        return [];
    }

    private function getLessons(object $object): array
    {
        if ($object instanceof ModuleInterface) {
            return $this->lessonRepository->getByModule($object);
        }

        if ($object instanceof CourseInterface) {
            return $this->lessonRepository->getByCourse($object);
        }

        return [];
    }
}
