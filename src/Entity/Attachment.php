<?php

namespace App\Entity;

use App\Model\AttachmentInterface;
use App\Model\LessonInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use DateTime;
use Symfony\Component\HttpFoundation\File\File;

class Attachment implements AttachmentInterface
{
    use PersistableAwareTrait, TimestampableAwareTrait;

    private $name;

    private $lesson;

    private $fileName;

    private $file;

    private $mimeType;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLesson(): ?LessonInterface
    {
        return $this->lesson;
    }

    public function setLesson(LessonInterface $lesson): void
    {
        $this->lesson = $lesson;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(File $file): void
    {
        $this->file = $file;
        $this->updated = new DateTime();
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): void
    {
        $this->mimeType = $mimeType;
    }
}
