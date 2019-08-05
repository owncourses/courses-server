<?php

namespace App\Entity;

use App\Model\BookmarkInterface;
use App\Model\LessonAwareTrait;
use App\Model\OptionalUserAwareTrait;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;

class Bookmark implements BookmarkInterface
{
    use PersistableAwareTrait;
    use TimestampableAwareTrait;
    use OptionalUserAwareTrait;
    use LessonAwareTrait;

    private $title;

    private $timeInSeconds = 0;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTimeInSeconds(): int
    {
        return $this->timeInSeconds;
    }

    public function setTimeInSeconds(int $timeInSeconds): void
    {
        $this->timeInSeconds = $timeInSeconds;
    }
}
