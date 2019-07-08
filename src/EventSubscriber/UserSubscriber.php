<?php

namespace App\EventSubscriber;

use App\Event\UserCreateEvent;
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

    public function __construct(MailerInterface $mailer, SettingsManagerInterface $settingsManager, string $cacheDir)
    {
        $this->mailer = $mailer;
        $this->settingsManager = $settingsManager;
        $this->cacheDir = $cacheDir;
    }

    public function onUserCreated(UserCreateEvent $event): void
    {
        $user = $event->getUser();
        $email = new TemplatedEmail();
        $email
            ->from(new NamedAddress(
                $this->getSetting('email_from_address', 'contact@owncourses.org'),
                $this->getSetting('email_from_name', 'OwnCourses Team')
            ))
            ->to($user->getEmail())
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

    public static function getSubscribedEvents()
    {
        return [
           UserCreateEvent::class => 'onUserCreated',
        ];
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
