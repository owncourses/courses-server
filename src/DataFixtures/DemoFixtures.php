<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Module;
use App\Model\CourseInterface;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class DemoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $course = $this->createCourse();
        $this->createLessons($manager, $course);
        $this->createAuthor($manager, $course);
        $manager->persist($course);

        $manager->flush();
    }

    private function createCourse(): CourseInterface
    {
        $course = new Course();
        $course->setTitle('From courses maker ZERO to HERO');
        $course->setDescription('Learn how to use OwnCourses and deliver amazing courses to your audience.');
        $course->setVisible(true);
        $course->setStartDate(new DateTime('now'));
        $course->setEndDate((new DateTime('now'))->modify('+10 years'));

        return $course;
    }

    private function createLessons(ObjectManager $manager, CourseInterface $course): void
    {
        $firstModule = new Module();
        $firstModule->setTitle('Installation');
        $firstModule->setDescription('Learn how to install OwnCourses (and where to host it).');
        $firstModule->setCourse($course);
        $manager->persist($firstModule);

        $firstLesson = new Lesson();
        $firstLesson->setTitle('Requirements');
        $firstLesson->setDescription('What do You need to install OwnCourses.');
        $firstLesson->setDurationInMinutes(10);
        $firstLesson->setEmbedCode('<p>No embed</p>');
        $firstLesson->setModule($firstModule);
        $manager->persist($firstLesson);

        $secondLesson = new Lesson();
        $secondLesson->setTitle('Installation steps');
        $secondLesson->setDescription('Install OwnCourses server and students app.');
        $secondLesson->setDurationInMinutes(25);
        $secondLesson->setEmbedCode('<p>No embed</p>');
        $secondLesson->setModule($firstModule);
        $manager->persist($secondLesson);

        $secondModule = new Module();
        $secondModule->setTitle('Configuration');
        $secondModule->setDescription('Learn how to configure OwnCourses.');
        $secondModule->setCourse($course);
        $manager->persist($secondModule);

        $thirdLesson = new Lesson();
        $thirdLesson->setTitle('Storage with AWS');
        $thirdLesson->setDescription('How to store courses and lessons covers and attachments on AWS.');
        $thirdLesson->setDurationInMinutes(15);
        $thirdLesson->setEmbedCode('<p>No embed</p>');
        $thirdLesson->setModule($secondModule);
        $manager->persist($thirdLesson);

        $fourthLesson = new Lesson();
        $fourthLesson->setTitle('Integrations via Zappier');
        $fourthLesson->setDescription('Learn how to use full OwnCourses power with Zappier integrations.');
        $fourthLesson->setDurationInMinutes(40);
        $fourthLesson->setEmbedCode('<p>No embed</p>');
        $fourthLesson->setModule($secondModule);
        $manager->persist($fourthLesson);
    }

    private function createAuthor(ObjectManager $manager, CourseInterface $course): void
    {
        $author = new Author();
        $author->setName('Paweł Mikołajczuk');
        $author->setBio('OwnCourses founder. OpenSource developer.');
        $author->addCourse($course);
        $manager->persist($author);
    }
}
