<?php

namespace App\EventSubscriber;

use App\Entity\Course;
use App\Event\NewCourseAddedEvent;
use App\Event\UserCreateEvent;
use App\Event\UserPasswordChangeRequestEvent;
use App\Model\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sentry\ClientInterface as SentryClient;
use SWP\Bundle\SettingsBundle\Context\ScopeContext;
use SWP\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class UserSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    private SettingsManagerInterface $settingsManager;

    private string $cacheDir;

    private string $studentsAppUrl;

    private SentryClient $sentryClient;

    private EntityManagerInterface $entityManager;

    public function __construct(
        MailerInterface $mailer,
        SettingsManagerInterface $settingsManager,
        string $cacheDir,
        string $studentsAppUrl,
        SentryClient $sentryClient,
        EntityManagerInterface $entityManager
    ) {
        $this->mailer = $mailer;
        $this->settingsManager = $settingsManager;
        $this->cacheDir = $cacheDir;
        $this->studentsAppUrl = $studentsAppUrl;
        $this->sentryClient = $sentryClient;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            UserCreateEvent::class => 'onUserCreated',
            UserPasswordChangeRequestEvent::class => 'onUserPasswordRequestReset',
            SecurityEvents::INTERACTIVE_LOGIN => 'onUserSuccessfulLogin',
            NewCourseAddedEvent::class => 'onCourseCreated',
        ];
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

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->sentryClient->captureException($e);
        }
    }

    public function onCourseCreated(NewCourseAddedEvent $event): void
    {
        $user = $event->user;
        /** @var Course[] $userCourses */
        $course = $event->course;

        $email = $this->createEmail($user);
        $email
            ->subject($this->getSetting('new_course_email_title', 'New course was added to your account'))
            ->html(
                $this->renderTemplateFromString($this->getSetting('new_course_email_template', 'CHANGE THIS EMAIL CONTENT IN OwnCourses SETTINGS! '), [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName(),
                    'email' => $user->getEmail(),
                    'course' => $course->getTitle(),
                ])
            )
        ;

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->sentryClient->captureException($e);
        }
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

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->sentryClient->captureException($e);
        }
    }

    public function onUserSuccessfulLogin(InteractiveLoginEvent $event): void
    {
        $user = $event->getAuthenticationToken()->getUser();
        if ($user instanceof UserInterface) {
            $user->setLastLoginDate(new \DateTime());
            $this->entityManager->flush();
        }
    }

    private function createEmail($user): TemplatedEmail
    {
        $email = new TemplatedEmail();
        $email
            ->from(new Address(
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
