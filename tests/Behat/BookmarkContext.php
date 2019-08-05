<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Bookmark;
use App\Repository\LessonRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use function array_key_exists;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;

final class BookmarkContext extends AbstractObjectContext implements Context
{
    private $entityManager;

    private $lessonRepository;

    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        LessonRepositoryInterface $lessonRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->lessonRepository = $lessonRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @Given the following Bookmarks:
     */
    public function theFollowingBookmarks(TableNode $table)
    {
        $metadata = $this->entityManager->getClassMetaData(Bookmark::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        foreach ($table as $row => $columns) {
            $bookmark = new Bookmark();

            if (array_key_exists('id', $columns)) {
                $bookmark->setId($columns['id']);
                unset($columns['id']);
            }

            if (array_key_exists('lesson', $columns)) {
                $columns['lesson'] = $this->lessonRepository->findOneBy(['title' => $columns['lesson']]);
            }

            if (array_key_exists('user', $columns)) {
                if ('null' === $columns['user']) {
                    $columns['user'] = null;
                } else {
                    $columns['user'] = $this->userRepository->getOneByEmail($columns['user']);
                }
            }

            $this->fillObject($bookmark, $columns);
            $this->entityManager->persist($bookmark);
        }

        $this->entityManager->flush();
    }
}
