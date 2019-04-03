<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CourseRepositoryInterface;
use App\Repository\ModuleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiCourseModulesController extends AbstractController
{
    private $courseRepository;
    private $moduleRepository;

    public function __construct(CourseRepositoryInterface $courseRepository, ModuleRepositoryInterface $moduleRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->moduleRepository = $moduleRepository;
    }

    public function getAllForCourse(SerializerInterface $serializer, int $courseId): Response
    {
        $course = $this->courseRepository->getOneById($courseId);
        if (null === $course) {
            throw new NotFoundHttpException('Course was not found');
        }

        $modules = $this->moduleRepository->getAllForCourse($course);

        return new Response($serializer->serialize($modules, 'json', ['groups' => ['course_modules']]));
    }
}
