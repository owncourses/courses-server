<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Lesson;
use App\Repository\ModuleRepositoryInterface;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\ORM\EntityManagerInterface;

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
     */
    public function lessonInWithDescriptionAndIdAndEmbedCode(string $lessonTitle, string $moduleTitle, string $lessonDescription, string $lessonUUID, PyStringNode $lessonEmbed)
    {
        $module = $this->moduleRepository->findOneBy(['title' => $moduleTitle]);
        if (null === $module) {
            throw new \Exception('Course was not found');
        }

        $metadata = $this->entityManager->getClassMetaData(Lesson::class);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

        $lesson = new Lesson();
        $lesson->setId($lessonUUID);
        $lesson->setModule($module);
        $lesson->setTitle($lessonTitle);
        $lesson->setDescription($lessonDescription);
        $lesson->setEmbedCode($lessonEmbed->getRaw());

        $this->entityManager->persist($lesson);
        $this->entityManager->flush();
    }
}
