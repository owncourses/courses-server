<?php

namespace App\Entity;

use App\Model\AuthorInterface;
use App\Model\CourseInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;

class Author implements AuthorInterface
{
    public const AUTHOR_GENDER_MALE = 'male';
    public const AUTHOR_GENDER_FEMALE = 'female';
    public const AUTHOR_GENDER_OTHER = 'other';

    use PersistableAwareTrait, TimestampableAwareTrait;

    private $name;

    private $bio;

    private $picture;

    private $pictureFile;

    private $courses;

    private $gender = self::AUTHOR_GENDER_MALE;

    public function __construct()
    {
        $this->setCourses(new ArrayCollection());
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(string $bio): void
    {
        $this->bio = $bio;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): void
    {
        $this->picture = $picture;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function getPictureFile(): ?File
    {
        return $this->pictureFile;
    }

    public function setPictureFile(File $pictureFile): void
    {
        $this->pictureFile = $pictureFile;
        $this->updated = new DateTimeImmutable();
    }

    public function setCourses(Collection $courses): void
    {
        $this->courses = $courses;
    }

    public function addCourse(CourseInterface $course): void
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }
    }

    public function removeCourse(CourseInterface $course): void
    {
        if (!$this->courses->contains($course)) {
            $this->courses->remove($course);
        }
    }

    public function __toString()
    {
        return $this->getName() ?? 'New Author';
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }
}
