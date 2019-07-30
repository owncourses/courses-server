<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

interface AuthorInterface extends TimestampableInterface, PersistableInterface
{
    public function getName(): ?string;

    public function setName(string $name): void;

    public function getBio(): ?string;

    public function setBio(string $bio): void;

    public function getPicture(): ?string;

    public function setPicture(string $picture): void;

    public function getCourses(): Collection;

    public function getPictureFile(): ?File;

    public function setPictureFile(File $pictureFile): void;

    public function setCourses(Collection $courses): void;

    public function addCourse(CourseInterface $course): void;

    public function removeCourse(CourseInterface $course): void;

    public function getGender(): string;

    public function setGender(string $gender): void;
}
