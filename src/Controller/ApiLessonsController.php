<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LessonProgressType;
use App\Provider\LessonProviderInterface;
use App\Provider\UserLessonProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiLessonsController extends AbstractController
{
    private LessonProviderInterface $lessonProvider;

    private SerializerInterface $serializer;

    public function __construct(LessonProviderInterface $lessonProvider, SerializerInterface $serializer)
    {
        $this->lessonProvider = $lessonProvider;
        $this->serializer = $serializer;
    }

    public function getSingleLesson(string $lessonId): Response
    {
        return new Response($this->serializer->serialize(
            $this->lessonProvider->getOneById($lessonId),
            'json',
            ['groups' => ['lesson_details']]
        ));
    }

    public function setProgress(
        Request $request,
        EntityManagerInterface $entityManager,
        UserLessonProviderInterface $userLessonProvider,
        FormFactoryInterface $formFactory,
        string $lessonId
    ): Response {
        $lesson = $this->lessonProvider->getOneById($lessonId);
        $form = $formFactory->createNamed('', LessonProgressType::class, [], ['method' => Request::METHOD_PUT]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userLesson = $userLessonProvider->getCurrentUserLesson($lesson);
            $userLesson->setCompleted((bool) $form->getData()['completed']);
            $entityManager->flush();

            return new Response($this->serializer->serialize($lesson, 'json', ['groups' => ['lesson_details']]), Response::HTTP_OK);
        }

        return new Response($this->serializer->serialize($form, 'json'), Response::HTTP_BAD_REQUEST);
    }
}
