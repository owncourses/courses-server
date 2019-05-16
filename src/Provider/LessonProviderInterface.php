<?php

declare(strict_types=1);

namespace App\Provider;

use App\Model\LessonInterface;

interface LessonProviderInterface
{
    public function getOneById(string $lessonId): LessonInterface;
}
