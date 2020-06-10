<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\Common\Collections\Collection;

interface UserInterface extends \Symfony\Component\Security\Core\User\UserInterface, TimestampableInterface, PersistableInterface
{
    public const EVENT_USER_CREATED = 'app.user.created';

    public function getEmail(): ?string;

    public function setEmail(string $email): void;

    public function setRoles(array $roles): void;

    public function setPassword(string $password): void;

    public function getPlainPassword(): ?string;

    public function setPlainPassword(?string $plainPassword): void;

    public function getFirstName(): ?string;

    public function setFirstName(string $firstName): void;

    public function getLastName(): ?string;

    public function setLastName(string $lastName): void;

    public function getCourses(): Collection;

    public function addCourse(CourseInterface $course): void;

    public function removeCourse(CourseInterface $course): void;

    public function getNotifications(): Collection;

    public function addNotification(NotificationInterface $notification): void;

    public function getPasswordNeedToBeChanged(): ?bool;

    public function setPasswordNeedToBeChanged($passwordNeedToBeChanged): void;

    public function getPasswordResetToken(): ?string;

    public function setPasswordResetToken(?string $passwordResetToken): void;

    public function getLastLoginDate(): ?\DateTime;

    public function setLastLoginDate(?\DateTime $lastLoginDate): void;
}
