<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\HttpFoundation\File\File;

interface AttachmentInterface extends TimestampableInterface, PersistableInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    public function getLesson(): ?LessonInterface;

    public function setLesson(LessonInterface $lesson): void;

    public function getFileName(): ?string;

    public function setFileName(?string $fileName): void;

    public function getFile(): ?File;

    public function setFile(File $file): void;

    public function getMimeType(): ?string;

    public function setMimeType(?string $mimeType): void;
}
