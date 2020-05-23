<?php

declare(strict_types=1);

namespace App\Model;

interface DemoLessonInterface extends TimestampableInterface, PersistableInterface
{
    public function getLesson(): ?LessonInterface;

    public function setLesson(?LessonInterface $lesson): void;
}
