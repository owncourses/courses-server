<?php

namespace App\Factory;

use App\Entity\User;
use App\Generator\StringGeneratorInterface;
use App\Model\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFactory implements UserFactoryInterface
{
    private UserPasswordEncoderInterface $passwordEncoder;

    protected StringGeneratorInterface $stringGenerator;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, StringGeneratorInterface $stringGenerator)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->stringGenerator = $stringGenerator;
    }

    public function create(): UserInterface
    {
        $user = new User();
        $plainPassword = $this->stringGenerator::random(7);
        $generatedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPlainPassword($plainPassword);
        $user->setPassword($generatedPassword);
        $user->setPasswordNeedToBeChanged(true);

        return $user;
    }
}
