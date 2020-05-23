<?php

namespace App\Entity;

use App\Model\CourseInterface;
use App\Model\NotificationInterface;
use App\Model\PersistableAwareTrait;
use App\Model\TimestampableAwareTrait;
use App\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class User implements UserInterface
{
    use TimestampableAwareTrait;
    use PersistableAwareTrait;

    private ?string $email = null;

    private array $roles = [];

    private ?string $password = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?string $plainPassword = null;

    private bool $passwordNeedToBeChanged;

    private ?string $passwordResetToken = null;

    private Collection $courses;

    private Collection $notifications;

    private ?\DateTime $lastLoginDate = null;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->passwordNeedToBeChanged = false;
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

    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(NotificationInterface $notification): void
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
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

    public function getPasswordResetToken(): ?string
    {
        return $this->passwordResetToken;
    }

    public function setPasswordResetToken(?string $passwordResetToken): void
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    public function getLastLoginDate(): ?\DateTime
    {
        return $this->lastLoginDate;
    }

    public function setLastLoginDate(?\DateTime $lastLoginDate): void
    {
        $this->lastLoginDate = $lastLoginDate;
    }
}
