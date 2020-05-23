<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\CourseInterface;
use App\Model\DemoLessonInterface;
use App\Model\LessonInterface;
use App\Model\ModuleInterface;
use App\Repository\CourseRepositoryInterface;
use App\Repository\DemoLessonRepositoryInterface;
use App\Repository\ModuleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiCourseModulesController extends AbstractController
{
    private CourseRepositoryInterface $courseRepository;

    private ModuleRepositoryInterface $moduleRepository;

    private DemoLessonRepositoryInterface $demoLessonRepository;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        ModuleRepositoryInterface $moduleRepository,
    DemoLessonRepositoryInterface $demoLessonRepository
    ) {
        $this->courseRepository = $courseRepository;
        $this->moduleRepository = $moduleRepository;
        $this->demoLessonRepository = $demoLessonRepository;
    }

    public function getAllForCourse(SerializerInterface $serializer, int $courseId): Response
    {
        $course = $this->courseRepository->getOneById($courseId);
        if (null === $course) {
            throw new NotFoundHttpException('Course was not found');
        }

        if (CourseInterface::COURSE_TYPE_DEMO === $course->getType() && null !== $course->getParent()) {
            $modules = $this->moduleRepository->getAllForCourse($course->getParent());
            $demoLessons = $this->demoLessonRepository->findAllForCourse($course->getParent());
            $enabledLessons = [];
            /** @var DemoLessonInterface $demoLesson */
            foreach ($demoLessons as $demoLesson) {
                $enabledLessons[] = $demoLesson->getLesson()->getId();
            }

            /** @var ModuleInterface $module */
            foreach ($modules as $module) {
                $module->setCourse($course);

                /** @var LessonInterface $lesson */
                foreach ($module->getLessons() as $lesson) {
                    if (!in_array($lesson->getId(), $enabledLessons)) {
                        $lesson->setBlocked(true);
                    }
                }
            }
        } else {
            $modules = $this->moduleRepository->getAllForCourse($course);
        }

        return new Response($serializer->serialize($modules, 'json', ['groups' => ['course_modules']]));
    }
}
