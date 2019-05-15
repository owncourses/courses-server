<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use App\Model\LessonInterface;
use App\Repository\UserLessonRepositoryInterface;
use Symfony\Component\Security\Core\Security;

final class LessonCompletedProcessor
{
    private $userLessonRepository;
    private $security;

    public function __construct(
        UserLessonRepositoryInterface $userLessonRepository,
        Security $security
    ) {
        $this->userLessonRepository = $userLessonRepository;
        $this->security = $security;
    }

    public function process(object $object, array $data): array
    {
        if ($object instanceof LessonInterface) {
            $userLesson = $this->userLessonRepository->getOneByUserAndLesson($this->security->getUser(), $object);
            if (null === $userLesson) {
                $data['completed'] = null;
            } else {
                $data['completed'] = $userLesson->getCompleted();
            }
        }

        return $data;
    }
}
