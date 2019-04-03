<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CourseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiCoursesController extends AbstractController
{
    private $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAll(SerializerInterface $serializer): Response
    {
        $courses = $this->courseRepository->getAll();

        return new Response($serializer->serialize($courses, 'json', ['groups' => ['list_courses']]));
    }
}
