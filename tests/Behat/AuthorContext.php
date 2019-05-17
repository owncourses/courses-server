<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use function explode;
use function array_key_exists;
use App\Entity\Author;
use App\Repository\CourseRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AuthorContext extends AbstractObjectContext implements Context
{
    private $entityManager;

    private $courseRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CourseRepositoryInterface $courseRepository
    ) {
        $this->entityManager = $entityManager;
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Given the following Authors:
     */
    public function theFollowingAuthors(TableNode $table)
    {
        foreach ($table as $row => $columns) {
            $author = new Author();

            if (array_key_exists('courses', $columns)) {
                $courses = explode(', ', $columns['courses']);
                foreach ($courses as $course) {
                    $author->addCourse($this->courseRepository->findOneBy(['title' => $course]));
                }
                unset($columns['courses']);
            }

            if (array_key_exists('picture', $columns)) {
                $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$columns['picture'];
                copy(__DIR__.'/../Resources/assets/'.$columns['picture'], $temp);
                $columns['pictureFile'] = new UploadedFile($temp, $columns['picture'], null, null, true);
            }

            $this->fillObject($author, $columns);
            $this->entityManager->persist($author);
        }

        $this->entityManager->flush();
    }
}
