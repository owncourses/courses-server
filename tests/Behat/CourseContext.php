<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Course;
use App\Entity\Module;
use App\Repository\CourseRepositoryInterface;
use function array_key_exists;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function sys_get_temp_dir;

final class CourseContext extends AbstractObjectContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var CourseRepositoryInterface
     */
    private $courseRepository;

    public function __construct(EntityManagerInterface $entityManager, CourseRepositoryInterface $courseRepository)
    {
        $this->courseRepository = $courseRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Given the following Courses:
     */
    public function theFollowingCourses(TableNode $table)
    {
        foreach ($table as $row => $columns) {
            $course = new Course();
            if (array_key_exists('coverImage', $columns)) {
                $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$columns['coverImage'];
                copy(__DIR__.'/../Resources/assets/'.$columns['coverImage'], $temp);
                $columns['coverImageFile'] = new UploadedFile($temp, $columns['coverImage'], null, null, true);
                unset($columns['coverImage']);
            }

            $this->fillObject($course, $columns);
            $this->entityManager->persist($course);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given Course :courseTitle module :moduleTitle with description :moduleDescription
     * @Given Course :courseTitle module :moduleTitle with description :moduleDescription and position :position
     */
    public function courseModuleWithDescription(string $courseTitle, string $moduleTitle, string $moduleDescription, int $position = -1)
    {
        $course = $this->courseRepository->findOneBy(['title' => $courseTitle]);
        $module = new Module();
        $module->setCourse($course);
        $module->setTitle($moduleTitle);
        $module->setDescription($moduleDescription);
        $module->setPosition($position);

        $this->entityManager->persist($module);
        $this->entityManager->flush();
    }

    /**
     * @Given Course :courseTitle and module :moduleTitle and id :moduleId
     */
    public function courseModuleWithCustomId(string $courseTitle, string $moduleTitle, string $moduleId)
    {
        $metadata = $this->entityManager->getClassMetaData(Module::class);
        $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());

        $course = $this->courseRepository->findOneBy(['title' => $courseTitle]);
        $module = new Module();
        $module->setId($moduleId);
        $module->setCourse($course);
        $module->setTitle($moduleTitle);
        $module->setDescription('default description');
        $module->setPosition(-1);

        $this->entityManager->persist($module);
        $this->entityManager->flush();
    }
}
