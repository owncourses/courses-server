<?php

declare(strict_types=1);

namespace App\Model;

interface BookmarkInterface extends TimestampableInterface, PersistableInterface, LessonAwareInterface, OptionalUserAwareInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getTimeInSeconds(): int;

    public function setTimeInSeconds(int $timeInSeconds): void;
}
