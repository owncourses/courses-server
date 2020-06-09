<?php

declare(strict_types=1);

namespace App\Event;

use App\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class NewCourseAddedEvent extends Event
{
    public UserInterface $user;
    public bool $onRegister;

    public function __construct(UserInterface $user, bool $onRegister)
    {
        $this->user = $user;
        $this->onRegister = $onRegister;
    }
}
