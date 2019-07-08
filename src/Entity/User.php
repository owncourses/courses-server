<?php

namespace App\Entity;

use App\Model\CourseInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use App\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    use TimestampableAwareTrait, PersistableAwareTrait;

    private $email;

    /**
     * @var string[]
     */
    private $roles = [];

    private $password;

    private $firstName;

    private $lastName;

    /**
     * @var string|null Temporary field used for password hashing
     */
    private $plainPassword;

    private $passwordNeedToBeChanged;

    private $courses;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(CourseInterface $course): void
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
        }
    }

    public function removeCourse(CourseInterface $course): void
    {
        if ($this->courses->contains($course)) {
            $this->courses->removeElement($course);
        }
    }

    public function getPasswordNeedToBeChanged(): ?bool
    {
        return $this->passwordNeedToBeChanged;
    }

    public function setPasswordNeedToBeChanged($passwordNeedToBeChanged): void
    {
        $this->passwordNeedToBeChanged = $passwordNeedToBeChanged;
    }
}
