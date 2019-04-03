<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\HttpFoundation\File\File;

interface CourseInterface extends TimestampableInterface
{
    public function getId(): ?int;

    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getCoverImageName(): ?string;

    public function setCoverImageName(string $coverImageName): void;

    public function setCoverImageFile(?File $imageFile = null): void;

    public function getCoverImageFile(): ?File;
}
