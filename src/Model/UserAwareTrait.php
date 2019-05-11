<?php

declare(strict_types=1);

namespace App\Model;

trait UserAwareTrait
{
    protected $user;

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): void
    {
        $this->user = $user;
    }
}
