<?php

namespace App\Entity;

use App\Model\LessonInterface;
use App\Model\PersistableAwareTrait;
use App\Model\SortableAwareTrait;
use App\Model\TimestampableAwareTrait;

class Lesson implements LessonInterface
{
    use TimestampableAwareTrait, SortableAwareTrait, PersistableAwareTrait;

    private $title;

    private $description;

    private $embedCode;

    private $module;

    private $completed;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getEmbedCode(): ?string
    {
        return $this->embedCode;
    }

    public function setEmbedCode(string $embedCode): void
    {
        $this->embedCode = $embedCode;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): void
    {
        $this->module = $module;
    }

    public function getCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(?bool $completed): void
    {
        $this->completed = $completed;
    }
}
