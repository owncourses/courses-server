<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AttachmentRepositoryInterface;
use App\Repository\CourseRepositoryInterface;
use SWP\Component\Common\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiCoursesController extends AbstractController
{
    private CourseRepositoryInterface $courseRepository;

    public function __construct(CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getAll(SerializerInterface $serializer): Response
    {
        $courses = $this->courseRepository->getAll();

        return new Response($serializer->serialize($courses, 'json', ['groups' => ['list_courses']]));
    }

    public function getCourseAttachments(
        SerializerInterface $serializer,
        AttachmentRepositoryInterface $attachmentRepository,
        int $courseId
    ): Response {
        $course = $this->courseRepository->getOneById($courseId);
        if (null === $course) {
            throw new NotFoundHttpException('Course was not found');
        }

        return new Response($serializer->serialize($attachmentRepository->getAllForCourse($course), 'json', ['groups' => ['attachments_list']]));
    }
}
