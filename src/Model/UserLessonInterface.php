<?php

declare(strict_types=1);

namespace App\Model;

interface UserLessonInterface extends UserAwareInterface, TimestampableInterface, PersistableInterface
{
    public function getCompleted(): ?bool;

    public function setCompleted(?bool $completed): void;

    public function getLesson(): LessonInterface;

    public function setLesson(LessonInterface $lesson): void;
}
