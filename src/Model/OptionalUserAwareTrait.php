<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

trait OptionalUserAwareTrait
{
    protected $user;

    public function getUser(): ?SymfonyUserInterface
    {
        return $this->user;
    }

    public function setUser(?SymfonyUserInterface $user): void
    {
        $this->user = $user;
    }
}
