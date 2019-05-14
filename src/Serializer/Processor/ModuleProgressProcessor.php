<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Model\UserLessonInterface;
use App\Repository\LessonRepositoryInterface;
use App\Repository\UserLessonRepositoryInterface;
use Symfony\Component\Security\Core\Security;

final class ModuleProgressProcessor
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

    public function process(ModuleInterface $object, array $data): array
    {
        $user = $this->security->getUser();
        if (null === $user) {
            return $data;
        }

        $userLessons = $this->userLessonRepository->getCompletedByUserAndModule($user, $object);
        $lessons = $this->lessonRepository->getByModule($object);

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
            'completed_percentage' => (count($userLessons) > 0 && count($lessons) > 0) ? floor((count($userLessons) / count($lessons)) * 100) : 0,
            'completed_time' => $completedTime,
            'total_duration' => $totalDuration,
        ];

        return $data;
    }
}
