<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Lesson;
use App\Repository\ModuleRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class LessonContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ModuleRepositoryInterface
     */
    private $moduleRepository;

    public function __construct(EntityManagerInterface $entityManager, ModuleRepositoryInterface $moduleRepository)
    {
        $this->moduleRepository = $moduleRepository;
        $this->entityManager = $entityManager;
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
            throw new Exception('Course was not found');
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
}
