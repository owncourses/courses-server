<?php

declare(strict_types=1);

namespace App\Event;

use App\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreateEvent extends Event
{
    protected UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
}
