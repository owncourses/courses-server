<?php

declare(strict_types=1);

namespace App\Model;

trait LessonAwareTrait
{
    private $lesson;

    public function getLesson(): ?LessonInterface
    {
        return $this->lesson;
    }

    public function setLesson(LessonInterface $lesson): void
    {
        $this->lesson = $lesson;
    }
}
