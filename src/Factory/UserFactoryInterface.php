<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\UserInterface;

interface UserFactoryInterface
{
    public function create(): UserInterface;
}
