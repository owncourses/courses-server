<?php

namespace spec\App\Serializer\Processor;

use App\Model\CourseInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Model\UserInterface;
use App\Model\UserLessonInterface;
use App\Repository\LessonRepositoryInterface;
use App\Repository\UserLessonRepositoryInterface;
use App\Serializer\Processor\ProgressProcessor;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Security;

class ProgressProcessorSpec extends ObjectBehavior
{
    public function let(
        UserLessonRepositoryInterface $userLessonRepository,
        Security $security,
        LessonRepositoryInterface $lessonRepository,
        UserLessonInterface $userLesson,
        UserInterface $user,
        ModuleInterface $module,
        LessonInterface $lesson,
        CourseInterface $course
    ): void {
        $lesson->getDurationInMinutes()->willReturn(35);
        $userLesson->getCompleted()->willReturn(true, false);
        $userLesson->getLesson()->willReturn($lesson);
        $userLessonRepository->getCompletedByUserInModule($user, $module)->willReturn([], [$userLesson]);
        $userLessonRepository->getCompletedByUserInCourse($user, $course)->willReturn([$userLesson]);
        $lessonRepository->getByModule($module)->willReturn([], [$lesson]);
        $lessonRepository->getByCourse($course)->willReturn([$lesson]);
        $security->getUser()->willReturn($user);

        $this->beConstructedWith($userLessonRepository, $lessonRepository, $security);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProgressProcessor::class);
    }

    public function it_calculates_progress(ModuleInterface $module, CourseInterface $course): void
    {
        $data = [];
        $this->process($module, $data)->shouldHaveKeyWithValue('progress', [
            'completed_lessons' => 0,
            'completed_percentage' => 0,
            'completed_time' => 0,
            'total_duration' => 0,
        ]);

        $this->process($module, $data)->shouldHaveKeyWithValue('progress', [
            'completed_lessons' => 1,
            'completed_percentage' => 100,
            'completed_time' => 35,
            'total_duration' => 35,
        ]);

        $this->process($course, $data)->shouldHaveKeyWithValue('progress', [
            'completed_lessons' => 1,
            'completed_percentage' => 100,
            'completed_time' => 35,
            'total_duration' => 35,
        ]);
    }
}
