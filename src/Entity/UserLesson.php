<?php

namespace App\Entity;

use App\Model\LessonInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use App\Model\UserAwareTrait;
use App\Model\UserLessonInterface;

class UserLesson implements UserLessonInterface
{
    use TimestampableAwareTrait, PersistableAwareTrait, UserAwareTrait;

    private $completed;

    private $lesson;

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(?bool $completed): void
    {
        $this->completed = $completed;
    }

    public function getLesson(): LessonInterface
    {
        return $this->lesson;
    }

    public function setLesson(LessonInterface $lesson): void
    {
        $this->lesson = $lesson;
    }
}
