<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

interface UserAwareInterface
{
    public function getUser(): SymfonyUserInterface;

    public function setUser(SymfonyUserInterface $user): void;
}
