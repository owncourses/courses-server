<?php

declare(strict_types=1);

namespace App\Event;

use App\Model\CourseInterface;
use App\Model\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class NewCourseAddedEvent extends Event
{
    public UserInterface $user;
    public CourseInterface $course;

    public function __construct(UserInterface $user, CourseInterface $course)
    {
        $this->user = $user;
        $this->course = $course;
    }
}
