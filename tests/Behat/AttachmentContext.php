<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Attachment;
use App\Repository\LessonRepositoryInterface;
use function array_key_exists;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AttachmentContext extends AbstractObjectContext implements Context
{
    private $entityManager;

    private $lessonRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        LessonRepositoryInterface $lessonRepository
    ) {
        $this->entityManager = $entityManager;
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * @Given the following Attachments:
     */
    public function theFollowingAttachments(TableNode $table)
    {
        $metadata = $this->entityManager->getClassMetaData(Attachment::class);
        $metadata->setIdGenerator(new AssignedGenerator());

        foreach ($table as $row => $columns) {
            $attachment = new Attachment();

            if (array_key_exists('lesson', $columns)) {
                $columns['lesson'] = $this->lessonRepository->findOneBy(['title' => $columns['lesson']]);
            }

            if (array_key_exists('file', $columns)) {
                $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$columns['file'];
                copy(__DIR__.'/../Resources/assets/'.$columns['file'], $temp);
                $columns['file'] = new UploadedFile($temp, $columns['file'], null, null, true);
            }

            $this->fillObject($attachment, $columns);
            $this->entityManager->persist($attachment);
        }

        $this->entityManager->flush();
    }
}
