<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Module;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

interface LessonInterface extends TimestampableInterface, SortableInterface, PersistableInterface
{
    public const EMBED_TYPE_CODE = 'code';

    public const EMBED_TYPE_VIMEO = 'vimeo';

    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function setEmbedType(string $embedType): void;

    public function getEmbedType(): string;

    public function getEmbedCode(): ?string;

    public function setEmbedCode(string $embedCode): void;

    public function getModule(): ?Module;

    public function setModule(?Module $module): void;

    public function getCoverImageName(): ?string;

    public function setCoverImageName(?string $coverImageName): void;

    public function setCoverImageFile(?File $coverImageFile = null): void;

    public function getCoverImageFile(): ?File;

    public function getDurationInMinutes(): int;

    public function setDurationInMinutes(int $durationInMinutes): void;

    public function getAttachments(): Collection;

    public function addAttachment(AttachmentInterface $attachment): void;

    public function removeAttachment(AttachmentInterface $attachment): void;

    public function isBlocked(): bool;

    public function setBlocked(bool $blocked): void;
}
