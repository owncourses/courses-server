<?php

declare(strict_types=1);

namespace App\Manager;

use App\Model\UserInterface;

interface UserManagerInterface
{
    public function addCourseByTitleOrSku(UserInterface $user, string $courseTitleOrSku): void;

    public function getOrCreateUser(string $email): UserInterface;

    public function setGeneratedPasswordResetToken(UserInterface $user): void;

    public function resetPassword(UserInterface $user, string $password): void;
}
