<?php

namespace App\Controller;

use App\Model\NotificationInterface;
use App\Model\UserInterface;
use App\Repository\NotificationRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use SWP\Component\Common\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiNotificationsController extends AbstractController
{
    protected SerializerInterface $serializer;

    private NotificationRepositoryInterface $notificationRepository;

    public function __construct(
        SerializerInterface $serializer,
        NotificationRepositoryInterface $notificationRepository
    )
    {
        $this->serializer = $serializer;
        $this->notificationRepository = $notificationRepository;
    }

    public function list(): Response
    {
        $notifications = $this->notificationRepository->findAll();
        /** @var UserInterface $user */
        $user = $this->getUser();

        return new Response($this->serializer->serialize(
            $this->getProcessedNotifications($notifications, $user),
            'json',
            ['groups' => ['list']])
        );
    }

    public function markAsRead(string $uuid, EntityManagerInterface $entityManager): Response
    {
        $notification = $this->notificationRepository->findOneBy(['id' =>$uuid]);
        if (null === $notification) {
            throw new NotFoundHttpException('Notification was not found');
        }

        /** @var UserInterface $user */
        $user = $this->getUser();
        $user->addNotification($notification);
        $entityManager->flush();

        $notifications = $this->notificationRepository->findAll();

        return new Response($this->serializer->serialize(
            $this->getProcessedNotifications($notifications, $user),
            'json',
            ['groups' => ['list']]
        ), 201);
    }


    private function getProcessedNotifications(array $notifications, UserInterface $user): array
    {
        $readByUserNotifications = \array_map(function ($notification) {
            return $notification->getId();
        }, $user->getNotifications()->toArray());

        $unreadNotification = count($notifications);
        if (0 === $unreadNotification) {
            return [
                'notifications' => $notifications,
                'unread' => $unreadNotification,
            ];
        }


        /** @var NotificationInterface $notification */
        foreach ($notifications as $notification) {
            if (in_array($notification->getId(), $readByUserNotifications)) {
                $notification->setRead(true);
                $unreadNotification--;
            }
        }

        return [
            'notifications' => $notifications,
            'unread' => $unreadNotification,
        ];
    }
}
