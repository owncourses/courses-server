<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Module;

interface LessonInterface extends TimestampableInterface, SortableInterface, PersistableInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getEmbedCode(): ?string;

    public function setEmbedCode(string $embedCode): void;

    public function getModule(): ?Module;

    public function setModule(?Module $module): void;

    public function getCompleted(): ?bool;

    public function setCompleted(?bool $completed): void;
}
