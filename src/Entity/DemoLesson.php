<?php

namespace App\Entity;

use App\Model\DemoLessonInterface;
use App\Model\LessonInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;

class DemoLesson implements DemoLessonInterface
{
    use TimestampableAwareTrait;
    use PersistableAwareTrait;

    private ?LessonInterface $lesson = null;

    public function getLesson(): ?LessonInterface
    {
        return $this->lesson;
    }

    public function setLesson(?LessonInterface $lesson): void
    {
        $this->lesson = $lesson;
    }
}
