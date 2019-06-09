<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class UserRegisteredListener
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function onAppUserCreated(GenericEvent $event): void
    {
        $email = (new Email())
            ->from('mikolajczuk.private@gmail.com')
            ->to('pawel@mikolajczuk.in')
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

//        $this->mailer->send($email);
    }
}
