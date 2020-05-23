<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Course;
use App\Entity\Module;
use App\Repository\CourseRepositoryInterface;
use function array_key_exists;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Id\AssignedGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function sys_get_temp_dir;

final class CourseContext extends AbstractObjectContext implements Context
{

    private EntityManagerInterface $entityManager;

    private CourseRepositoryInterface $courseRepository;

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
        $courses = [];

        foreach ($table as $row => $columns) {
            foreach ($columns as $key => $columnValue) {
                if ('null' === $columnValue) {
                    $columns[$key] = null;
                }
            }

            $course = new Course();
            if (array_key_exists('id', $columns)) {
                $metadata = $this->entityManager->getClassMetaData(Course::class);
                $metadata->setIdGenerator(new AssignedGenerator());
                $course->setId($columns['id']);
                unset($columns['id']);
            }

            if (array_key_exists('coverImage', $columns)) {
                $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR.$columns['coverImage'];
                copy(__DIR__.'/../Resources/assets/'.$columns['coverImage'], $temp);
                $columns['coverImageFile'] = new UploadedFile($temp, $columns['coverImage'], null, null, true);
                unset($columns['coverImage']);
            }

            if (array_key_exists('startDate', $columns)) {
                if (null === $columns['startDate']) {
                    unset($columns['startDate']);
                } else {
                    $date = new DateTime();
                    $date->modify($columns['startDate']);
                    $columns['startDate'] = $date;
                }
            }

            if (array_key_exists('endDate', $columns)) {
                if (null === $columns['endDate']) {
                    unset($columns['endDate']);
                } else {
                    $date = new DateTime();
                    $date->modify($columns['endDate']);
                    $columns['endDate'] = $date;
                }
            }

            if (array_key_exists('visible', $columns)) {
                $columns['visible'] = 'true' === $columns['visible'];
            }

            if (array_key_exists('parent', $columns) && null !== $columns['parent']) {
                $course->setParent($courses[$columns['parent']]);
                unset($columns['parent']);
            }

            $this->fillObject($course, $columns);
            $this->entityManager->persist($course);
            $courses[$course->getTitle()] = $course;
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
     * @Given Module :moduleTitle with id :moduleId for course :courseTitle
     */
    public function moduleWithCustomIdForCourse(string $moduleTitle, string $moduleId, string $courseTitle)
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
