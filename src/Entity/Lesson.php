<?php

namespace App\Entity;

use App\Model\AttachmentInterface;
use App\Model\LessonInterface;
use App\Model\PersistableAwareTrait;
use App\Model\SortableAwareTrait;
use App\Model\TimestampableAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

class Lesson implements LessonInterface
{
    use TimestampableAwareTrait;
    use SortableAwareTrait;
    use PersistableAwareTrait;

    private ?string $title = null;

    private ?string $description = null;

    private string $embedType = LessonInterface::EMBED_TYPE_CODE;

    private ?string $embedCode = null;

    private ?Module $module = null;

    private ?string $coverImageName = null;

    private int $durationInMinutes = 0;

    private Collection $attachments;

    private ?File $coverImageFile = null;

    private bool $blocked = false;

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
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

    public function setEmbedType(string $embedType): void
    {
        $this->embedType = $embedType;
    }

    public function getEmbedType(): string
    {
        return $this->embedType;
    }

    public function getEmbedCode(): ?string
    {
        return $this->embedCode;
    }

    public function setEmbedCode(string $embedCode): void
    {
        $this->embedCode = $embedCode;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): void
    {
        $this->module = $module;
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
        $this->updated = new DateTime();
    }

    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    public function getDurationInMinutes(): int
    {
        return $this->durationInMinutes;
    }

    public function setDurationInMinutes(int $durationInMinutes): void
    {
        $this->durationInMinutes = $durationInMinutes;
    }

    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(AttachmentInterface $attachment): void
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
        }
    }

    public function removeAttachment(AttachmentInterface $attachment): void
    {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
        }
    }

    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): void
    {
        $this->blocked = $blocked;
    }

    public function __toString()
    {
        return (string) $this->getTitle();
    }
}
