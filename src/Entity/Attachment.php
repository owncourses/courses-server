<?php

namespace App\Entity;

use App\Model\AttachmentInterface;
use App\Model\LessonAwareTrait;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;

class Attachment implements AttachmentInterface
{
    use PersistableAwareTrait;
    use TimestampableAwareTrait;
    use LessonAwareTrait;

    private $name;

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
        $this->updated = new DateTimeImmutable();
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
