<?php

declare(strict_types=1);

namespace App\Model;

interface UserAwareInterface
{
    public function getUser(): UserInterface;

    public function setUser(UserInterface $user): void;
}
