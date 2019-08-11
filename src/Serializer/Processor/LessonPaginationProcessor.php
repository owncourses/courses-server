<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use App\Model\LessonInterface;
use App\Repository\LessonRepositoryInterface;

final class LessonPaginationProcessor
{
    private $lessonRepository;

    public function __construct(
        LessonRepositoryInterface $lessonRepository
    ) {
        $this->lessonRepository = $lessonRepository;
    }

    public function process(object $object, array $data): array
    {
        if ($object instanceof LessonInterface) {
            $previous = $this->lessonRepository->getPreviousInModule($object);
            $next = $this->lessonRepository->getNextInModule($object);
            $data['pagination'] = array_merge(isset($data['pagination']) ? $data['pagination'] : [], [
                'prev_lesson_id' => $previous ? $previous->getId() : null,
                'next_lesson_id' => $next ? $next->getId() : null,
            ]);
        }

        return $data;
    }
}
