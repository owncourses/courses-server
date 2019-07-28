<?php

namespace App\EventSubscriber;

use App\Event\UserCreateEvent;
use App\Event\UserPasswordChangeRequestEvent;
use SWP\Bundle\SettingsBundle\Context\ScopeContext;
use SWP\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\NamedAddress;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class UserSubscriber implements EventSubscriberInterface
{
    private $mailer;

    private $settingsManager;

    private $cacheDir;

    private $studentsAppUrl;

    public function __construct(
        MailerInterface $mailer,
        SettingsManagerInterface $settingsManager,
        string $cacheDir,
        string $studentsAppUrl
    ) {
        $this->mailer = $mailer;
        $this->settingsManager = $settingsManager;
        $this->cacheDir = $cacheDir;
        $this->studentsAppUrl = $studentsAppUrl;
    }

    public function onUserCreated(UserCreateEvent $event): void
    {
        $user = $event->getUser();
        $email = $this->createEmail($user);
        $email
            ->subject($this->getSetting('new_user_email_title', 'Welcome in OwnCourses'))
            ->html(
                $this->renderTemplateFromString($this->getSetting('new_user_email_template', 'CHANGE THIS EMAIL CONTENT IN OwnCourses SETTINGS! '), [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'temporaryPassword' => $user->getPlainPassword(),
                ])
            )
        ;

        $this->mailer->send($email);
    }

    public function onUserPasswordRequestReset(UserPasswordChangeRequestEvent $event): void
    {
        $user = $event->getUser();
        $email = $this->createEmail($user);
        $email
            ->subject($this->getSetting('password_reset_email_title', 'Password reset in OwnCourses'))
            ->html(
                $this->renderTemplateFromString($this->getSetting('password_reset_email_template', 'CHANGE THIS EMAIL CONTENT IN OwnCourses SETTINGS! '), [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'resetUrl' => $this->studentsAppUrl.'/reset?token='.$user->getPasswordResetToken(),
                ])
            )
        ;

        $this->mailer->send($email);
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreateEvent::class => 'onUserCreated',
            UserPasswordChangeRequestEvent::class => 'onUserPasswordRequestReset',
        ];
    }

    private function createEmail($user): TemplatedEmail
    {
        $email = new TemplatedEmail();
        $email
            ->from(new NamedAddress(
                $this->getSetting('email_from_address', 'contact@owncourses.org'),
                $this->getSetting('email_from_name', 'OwnCourses Team')
            ))
            ->to($user->getEmail());

        return $email;
    }

    private function getSetting(string $name, string $default)
    {
        return $this->settingsManager->get($name, ScopeContext::SCOPE_GLOBAL, null, $default);
    }

    private function renderTemplateFromString(string $template, array $context): string
    {
        $loader = new ArrayLoader(['template' => $template]);
        $twig = new Environment($loader, ['cache' => $this->cacheDir.DIRECTORY_SEPARATOR.'twig']);

        return $twig->render('template', $context);
    }
}
