<?php

namespace App\Provider;

use App\Model\LessonInterface;
use App\Repository\LessonRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LessonProvider implements LessonProviderInterface
{
    private $lessonsRepository;

    public function __construct(LessonRepositoryInterface $lessonsRepository)
    {
        $this->lessonsRepository = $lessonsRepository;
    }

    public function getOneById(string $lessonId): LessonInterface
    {
        $lesson = $this->lessonsRepository->getOneById($lessonId);
        if (
            null === $lesson ||
            !$lesson->getModule() ||
            !$lesson->getModule()->getCourse() ||
            !$lesson->getModule()->getCourse()->isActive()
        ) {
            throw new NotFoundHttpException('Lesson was not found.');
        }

        return $lesson;
    }
}
