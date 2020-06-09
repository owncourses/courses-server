<?php

namespace App\Command;

use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use function in_array;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserPromoteCommand extends Command
{
    protected static $defaultName = 'app:user:promote';

    private UserRepositoryInterface $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepositoryInterface $userRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add new role to user')
            ->addArgument('user', InputArgument::REQUIRED, 'User email')
            ->addArgument('role', InputArgument::REQUIRED, 'User role')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $userEmail = (string) $input->getArgument('user');
        $role = (string) $input->getArgument('role');

        $user = $this->userRepository->getOneByEmail($userEmail);
        if (null === $user) {
            throw new Exception('User with provided email was not found!');
        }

        $userRoles = $user->getRoles();
        if (!in_array($role, $userRoles, true)) {
            $userRoles[] = $role;
            $user->setRoles($userRoles);
            $this->entityManager->flush();

            $io->success(sprintf('Role %s was successfully added to user %s', $role, $userEmail));

            return 0;
        }

        $io->success(sprintf('User %s already have role %s', $userEmail, $role));

        return 1;
    }
}
