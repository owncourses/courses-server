<?php

declare(strict_types=1);

namespace App\Model;

interface ModuleInterface extends TimestampableInterface, SortableInterface, PersistableInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getCourse(): ?CourseInterface;

    public function setCourse(?CourseInterface $course): void;
}
