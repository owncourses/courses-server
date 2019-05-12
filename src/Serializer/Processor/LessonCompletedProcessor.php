<?php

declare(strict_types=1);

namespace App\Serializer\Processor;

use App\Model\LessonInterface;
use App\Repository\UserLessonRepositoryInterface;
use Symfony\Component\Security\Core\Security;

class LessonCompletedProcessor
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

    public function process($object, array $data): array
    {
        if ($object instanceof LessonInterface) {
            $userLesson = $this->userLessonRepository->findOneBy(
                ['user' => $this->security->getUser(), 'lesson' => $object]
            );

            if (null === $userLesson) {
                $data['completed'] = null;
            } else {
                $data['completed'] = $userLesson->getCompleted();
            }
        }

        return $data;
    }
}
