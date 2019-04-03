<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\LessonRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiLessonsController extends AbstractController
{
    private $lessonsRepository;

    public function __construct(LessonRepositoryInterface $lessonsRepository)
    {
        $this->lessonsRepository = $lessonsRepository;
    }

    public function getSingleModule(SerializerInterface $serializer, string $lessonId): Response
    {
        $lesson = $this->lessonsRepository->findOneById($lessonId);
        if (null === $lesson) {
            throw new NotFoundHttpException('Lesson was not found');
        }

        return new Response($serializer->serialize($lesson, 'json', ['groups' => ['lesson_details']]));
    }
}
