<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bookmark;
use App\Form\BookmarkType;
use App\Form\ErrorHandler;
use App\Model\BookmarkInterface;
use App\Repository\BookmarkRepositoryInterface;
use App\Repository\LessonRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use SWP\Component\Common\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiBookmarksController extends AbstractController
{
    private $bookmarkRepository;

    private $lessonRepository;

    private $serializer;

    public function __construct(
        BookmarkRepositoryInterface $bookmarkRepository,
        LessonRepositoryInterface $lessonRepository,
        SerializerInterface $serializer
    ) {
        $this->bookmarkRepository = $bookmarkRepository;
        $this->lessonRepository = $lessonRepository;
        $this->serializer = $serializer;
    }

    public function getAllForLesson(SerializerInterface $serializer, string $lessonId): Response
    {
        $lesson = $this->lessonRepository->getOneById($lessonId);
        if (null === $lesson) {
            throw new NotFoundHttpException('Lesson was not found');
        }

        $bookmarks = $this->bookmarkRepository->getAllForLessonAndUser($lesson, $this->getUser());

        return new Response($serializer->serialize($bookmarks, 'json', ['groups' => ['bookmarks_list']]));
    }

    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        FormFactoryInterface $formFactory,
        string $lessonId
    ): Response {
        $lesson = $this->lessonRepository->getOneById($lessonId);
        if (null === $lesson) {
            throw new NotFoundHttpException('Lesson was not found');
        }

        $form = $formFactory->createNamed('', BookmarkType::class, new Bookmark());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BookmarkInterface $bookmark */
            $bookmark = $form->getData();
            $bookmark->setLesson($lesson);
            $bookmark->setUser($this->getUser());

            $entityManager->persist($bookmark);
            $entityManager->flush();

            return new Response($this->serializer->serialize($bookmark, 'json', ['groups' => ['bookmark_details']]), Response::HTTP_CREATED);
        }

        return new Response($this->serializer->serialize(ErrorHandler::getErrorsFromForm($form), 'json'), Response::HTTP_BAD_REQUEST);
    }

    public function delete(
        EntityManagerInterface $entityManager,
        string $bookmarkId
    ): Response {
        $bookmark = $this->bookmarkRepository->getOneById($bookmarkId);
        if (null === $bookmark) {
            throw new NotFoundHttpException('Bookmark was not found');
        }

        if ($bookmark->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($bookmark);
        $entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
