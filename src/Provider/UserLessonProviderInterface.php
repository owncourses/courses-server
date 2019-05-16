<?php

declare(strict_types=1);

namespace App\Provider;

use App\Model\LessonInterface;
use App\Model\UserLessonInterface;

interface UserLessonProviderInterface
{
    public function getCurrentUserLesson(LessonInterface $lesson): UserLessonInterface;
}
