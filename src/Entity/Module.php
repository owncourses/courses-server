<?php

namespace App\Entity;

use App\Model\CourseInterface;
use App\Model\ModuleInterface;
use App\Model\PersistableAwareTrait;
use App\Model\SortableAwareTrait;
use App\Model\TimestampableAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Module implements ModuleInterface
{
    use TimestampableAwareTrait, SortableAwareTrait, PersistableAwareTrait;

    private $title;

    private $description;

    private $course;

    private $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

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

    public function getCourse(): ?CourseInterface
    {
        return $this->course;
    }

    public function setCourse(?CourseInterface $course): void
    {
        $this->course = $course;
    }

    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function __toString()
    {
        return '['.$this->course->getTitle().'] '.$this->title;
    }
}
