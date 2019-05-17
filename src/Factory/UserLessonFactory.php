<?php

namespace App\Factory;

use App\Entity\UserLesson;
use App\Model\LessonInterface;
use App\Model\UserLessonInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserLessonFactory implements UserLessonFactoryInterface
{
    public function create(UserInterface $user, LessonInterface $lesson): UserLessonInterface
    {
        $userLesson = new UserLesson();
        $userLesson->setUser($user);
        $userLesson->setLesson($lesson);

        return $userLesson;
    }
}
