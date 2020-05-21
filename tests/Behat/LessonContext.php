<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\DemoLesson;
use App\Entity\Lesson;
use App\Repository\LessonRepositoryInterface;
use App\Repository\ModuleRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class LessonContext extends AbstractObjectContext implements Context
{
    private EntityManagerInterface $entityManager;

    private ModuleRepositoryInterface $moduleRepository;

    private LessonRepositoryInterface $lessonRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ModuleRepositoryInterface $moduleRepository,
        LessonRepositoryInterface $lessonRepository
    ) {
        $this->moduleRepository = $moduleRepository;
        $this->entityManager = $entityManager;
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * @Given Lesson :lessonTitle in :moduleTitle with description :lessonDescription and id :lessonUUID and embed code:
     * @Given Lesson :lessonTitle in :moduleTitle with description :lessonDescription and id :lessonUUID and coverImage :coverImage and embed code:
     */
    public function lessonInWithDescriptionAndIdAndEmbedCode(
        string $lessonTitle,
        string $moduleTitle,
        string $lessonDescription,
        string $lessonUUID,
        string $coverImage = null,
        PyStringNode $lessonEmbed = null
    ) {
        $module = $this->moduleRepository->findOneBy(['title' => $moduleTitle]);
        if (null === $module) {
            throw new Exception('Module was not found');
        }

        $metadata = $this->entityManager->getClassMetaData(Lesson::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        $lesson = new Lesson();
        $lesson->setId($lessonUUID);
        $lesson->setModule($module);
        $lesson->setTitle($lessonTitle);
        $lesson->setDescription($lessonDescription);
        if (null !== $lessonEmbed) {
            $lesson->setEmbedCode($lessonEmbed->getRaw());
        }

        if (null !== $coverImage) {
            $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$coverImage;
            copy(__DIR__.'/../Resources/assets/'.$coverImage, $temp);
            $lesson->setCoverImageFile(new UploadedFile($temp, $coverImage, null, null, true));
        }

        $this->entityManager->persist($lesson);
        $this->entityManager->flush();
    }

    /**
     * @Given the following Demo Lessons:
     */
    public function theFollowingDemoLessons(TableNode $table)
    {
        $metadata = $this->entityManager->getClassMetaData(DemoLesson::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        foreach ($table as $row => $columns) {
            $demoLesson = new DemoLesson();
            if (array_key_exists('id', $columns)) {
                $demoLesson->setId($columns['id']);
                unset($columns['id']);
            }

            if (array_key_exists('lesson', $columns)) {
                $lesson = $this->lessonRepository->findOneBy(['id' => $columns['lesson']]);
                if (null === $lesson) {
                    throw new Exception('Lesson was not found');
                }
                $columns['lesson'] = $lesson;
            }

            $this->fillObject($demoLesson, $columns);
            $this->entityManager->persist($demoLesson);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given the following Lessons:
     */
    public function theFollowingLessons(TableNode $table)
    {
        $metadata = $this->entityManager->getClassMetaData(Lesson::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        foreach ($table as $row => $columns) {
            $lesson = new Lesson();
            if (array_key_exists('id', $columns)) {
                $lesson->setId($columns['id']);
                unset($columns['id']);
            }

            if (array_key_exists('coverImage', $columns)) {
                $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$columns['coverImage'];
                copy(__DIR__.'/../Resources/assets/'.$columns['coverImage'], $temp);
                $columns['coverImageFile'] = new UploadedFile($temp, $columns['coverImage'], null, null, true);
                unset($columns['coverImage']);
            }

            if (array_key_exists('module', $columns)) {
                $module = $this->moduleRepository->findOneBy(['title' => $columns['module']]);
                if (null === $module) {
                    throw new Exception('Module was not found');
                }
                $columns['module'] = $module;
            }

            $this->fillObject($lesson, $columns);
            $this->entityManager->persist($lesson);
        }

        $this->entityManager->flush();
    }
}
