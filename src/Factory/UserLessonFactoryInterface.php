<?php

declare(strict_types=1);

namespace App\Factory;

use App\Model\LessonInterface;
use App\Model\UserLessonInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserLessonFactoryInterface
{
    public function create(UserInterface $user, LessonInterface $lesson): UserLessonInterface;
}
