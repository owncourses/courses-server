<?php

namespace App\Entity;

use App\Model\CourseInterface;
use App\Model\TimestampableAwareTrait;
use Symfony\Component\HttpFoundation\File\File;

class Course implements CourseInterface
{
    use TimestampableAwareTrait;

    private $id;

    private $title;

    private $description;

    private $coverImageName;

    /**
     * Hack for PropertyInfo issues with File.
     *
     * @var null
     */
    private $coverImageFile;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCoverImageName(): ?string
    {
        return $this->coverImageName;
    }

    public function setCoverImageName(?string $coverImageName): void
    {
        $this->coverImageName = $coverImageName;
    }

    public function setCoverImageFile(?File $coverImageFile = null): void
    {
        $this->coverImageFile = $coverImageFile;
        $this->updated = new \DateTime();
    }

    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    public function __toString()
    {
        return (string) ($this->title ?? 'Nowy Kurs');
    }
}
