<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

interface CourseInterface extends TimestampableInterface, VisibilityInterface, TimeLimitedInterface
{
    const COURSE_TYPE_STANDARD = 'standard';

    const COURSE_TYPE_DEMO = 'demo';

    public function getId(): ?int;

    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getDescription(): ?string;

    public function setDescription(string $description): void;

    public function getSku(): ?string;

    public function setSku(string $sku): void;

    public function getCoverImageName(): ?string;

    public function setCoverImageName(string $coverImageName): void;

    public function setCoverImageFile(?File $imageFile = null): void;

    public function getCoverImageFile(): ?File;

    public function getAuthors(): Collection;

    public function setAuthors(Collection $authors): void;

    public function getType(): string;

    public function setType(string $type): void;

    public function getPurchaseUrl(): ?string;

    public function setPurchaseUrl(?string $purchaseUrl): void;

    public function getParent(): ?CourseInterface;

    public function setParent(?CourseInterface $parent): void;
}
