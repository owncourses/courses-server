<?php

declare(strict_types=1);

namespace App\Model;

interface LessonAwareInterface
{
    public function getLesson(): ?LessonInterface;

    public function setLesson(LessonInterface $lesson): void;
}
