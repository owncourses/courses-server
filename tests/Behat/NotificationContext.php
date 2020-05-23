<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Notification;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;

final class NotificationContext extends AbstractObjectContext implements Context
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Given the following Notifications:
     */
    public function theFollowingNotifications(TableNode $table)
    {
        $metadata = $this->entityManager->getClassMetaData(Notification::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        foreach ($table as $row => $columns) {
            $notification = new Notification();
            if (array_key_exists('id', $columns)) {
                $notification->setId($columns['id']);
                unset($columns['id']);
            }

            $this->fillObject($notification, $columns);
            $this->entityManager->persist($notification);
        }

        $this->entityManager->flush();
    }
}
