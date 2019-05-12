<?php

namespace spec\App\Serializer\Processor;

use App\Model\LessonInterface;
use App\Model\UserInterface;
use App\Model\UserLessonInterface;
use App\Repository\UserLessonRepositoryInterface;
use App\Serializer\Processor\LessonCompletedProcessor;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Security\Core\Security;

class LessonCompletedProcessorSpec extends ObjectBehavior
{
    public function let(
        UserLessonRepositoryInterface $userLessonRepository,
        Security $security,
        UserLessonInterface $userLesson,
        UserInterface $user,
        LessonInterface $lesson
    ): void {
        $userLesson->getCompleted()->willReturn(true, false);
        $userLessonRepository->getOneByUserAndLesson($user, $lesson)->willReturn(null, $userLesson);
        $security->getUser()->willReturn($user);

        $this->beConstructedWith($userLessonRepository, $security);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(LessonCompletedProcessor::class);
    }

    public function it_sets_lesson_as_completed_when_needed(LessonInterface $lesson)
    {
        $data = [];
        $this->process($lesson, $data)->shouldHaveKeyWithValue('completed', null);
        $this->process($lesson, $data)->shouldHaveKeyWithValue('completed', true);
        $this->process($lesson, $data)->shouldHaveKeyWithValue('completed', false);
    }
}
