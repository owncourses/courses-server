<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;

interface ModuleInterface extends TimestampableInterface, SortableInterface, PersistableInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getCourse(): ?CourseInterface;

    public function setCourse(?CourseInterface $course): void;

    public function getLessons(): Collection;
}
