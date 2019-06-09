<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\CourseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user:create';

    private $courseRepository;
    private $userRepository;
    private $entityManager;
    private $passwordEncoder;

    public function __construct(
        CourseRepositoryInterface $courseRepository,
        UserRepositoryInterface $userRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct();
        $this->courseRepository = $courseRepository;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Create new user')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('firstName', InputArgument::REQUIRED, 'User first name')
            ->addArgument('lastName', InputArgument::REQUIRED, 'User last name')
            ->addOption('courses', null, InputOption::VALUE_REQUIRED, 'Courses assigned to user("," sperated by title)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');

        if (!is_string($email)) {
            throw new InvalidArgumentException('Provided email must be string!');
        }

        $user = $this->userRepository->getOneByEmail($email);
        if (null !== $user) {
            throw new Exception('User with provided email already exists!');
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_USER']);
        $generatedPassword = $this->passwordEncoder->encodePassword($user, (string) $input->getArgument('password'));
        $user->setPassword($generatedPassword);
        $user->setFirstName((string) $input->getArgument('firstName'));
        $user->setLastName((string) $input->getArgument('lastName'));

        if ($input->hasOption('courses')) {
            $coursesTitles = explode(',', $input->getOption('courses'));
            foreach ($coursesTitles as $coursesTitle) {
                $course = $this->courseRepository->findOneBy(['title' => $coursesTitle]);
                if (null !== $course) {
                    $user->addCourse($course);
                }
            }
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('User was created successfully.'));
        $io->note('Use app:user:promote to add new roles for this user');
    }
}
