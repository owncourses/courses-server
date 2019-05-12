<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserLesson;
use App\Form\LessonProgressType;
use App\Model\LessonInterface;
use App\Repository\LessonRepositoryInterface;
use App\Repository\UserLessonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiLessonsController extends AbstractController
{
    private $lessonsRepository;

    private $serializer;

    public function __construct(LessonRepositoryInterface $lessonsRepository, SerializerInterface $serializer)
    {
        $this->lessonsRepository = $lessonsRepository;
        $this->serializer = $serializer;
    }

    public function getSingleModule(string $lessonId): Response
    {
        $lesson = $this->getLessonById($lessonId);

        return new Response($this->serializer->serialize($lesson, 'json', ['groups' => ['lesson_details']]));
    }

    public function setProgress(
        Request $request,
        EntityManagerInterface $entityManager,
        UserLessonRepositoryInterface $userLessonRepository,
        Security $security,
        FormFactoryInterface $formFactory,
        string $lessonId
    ): Response {
        $lesson = $this->getLessonById($lessonId);
        $form = $formFactory->createNamed('', LessonProgressType::class, [], ['method' => Request::METHOD_PATCH]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userLesson = $userLessonRepository->getOneByUserAndLesson($security->getUser(), $lesson);
            if (!$userLesson) {
                $userLesson = new UserLesson();
                $userLesson->setUser($security->getUser());
                $userLesson->setLesson($lesson);
                $entityManager->persist($userLesson);
            }

            $userLesson->setCompleted((bool) $form->getData()['completed']);
            $entityManager->flush();

            return new Response($this->serializer->serialize($lesson, 'json', ['groups' => ['lesson_details']]), Response::HTTP_OK);
        }

        return new Response($this->serializer->serialize($form, 'json'), Response::HTTP_BAD_REQUEST);
    }

    private function getLessonById(string $lessonId): LessonInterface
    {
        $lesson = $this->lessonsRepository->getOneById($lessonId);
        if (null === $lesson || !$lesson->getModule()->getCourse()->isActive()) {
            throw new NotFoundHttpException('Lesson was not found');
        }

        return $lesson;
    }
}
