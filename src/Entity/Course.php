<?php

namespace App\Entity;

use App\Model\CourseInterface;
use App\Model\TimeLimitedAwareTrait;
use App\Model\TimestampableAwareTrait;
use App\Model\VisibilityAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\File\File;

class Course implements CourseInterface
{
    use TimestampableAwareTrait;
    use VisibilityAwareTrait;
    use TimeLimitedAwareTrait;

    private ?int $id = null;

    private ?string $title = null;

    private ?string $description = null;

    private ?string $sku = null;

    private ?string $coverImageName = null;

    private ?File $coverImageFile = null;

    private Collection $authors;

    private string $type = CourseInterface::COURSE_TYPE_STANDARD;

    private ?string $purchaseUrl = null;

    private ?CourseInterface $parent = null;

    public function __construct()
    {
        $this->setVisible(true);
        $this->setAuthors(new ArrayCollection());
    }

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

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
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

    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function setAuthors(Collection $authors): void
    {
        $this->authors = $authors;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getPurchaseUrl(): ?string
    {
        return $this->purchaseUrl;
    }

    public function setPurchaseUrl(?string $purchaseUrl): void
    {
        $this->purchaseUrl = $purchaseUrl;
    }

    public function getParent(): ?CourseInterface
    {
        return $this->parent;
    }

    public function setParent(?CourseInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function __toString()
    {
        return (string) ($this->title ?? 'New course');
    }
}
