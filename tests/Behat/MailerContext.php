<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behatch\Context\BaseContext;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Symfony\Component\Mailer\DataCollector\MessageDataCollector;
use Symfony\Component\Mailer\Event\MessageEvents;

final class MailerContext extends BaseContext
{
    /**
     * @Then Mail with title :title should be sent
     */
    public function mailWithTitleShouldBeSent(string $title): void
    {
        /** @var MessageDataCollector $events */
        $collector = $this->getSymfonyProfile()->getCollector('mailer');
        /** @var TemplatedEmail[] $messages */
        $messages = $collector->getEvents()->getMessages();
        if (0 === count($messages)) {
            throw new \Exception('No messages was sent.');
        }
        $sentMessagesTitles = [];
        foreach ($messages as $message) {
            $messageTitle = $message->getHeaders()->get('subject')->getBody();
            $sentMessagesTitles[] = $messageTitle;
            if (false !== strpos($messageTitle, $title)) {
                return;
            }
        }

        throw new \Exception(sprintf('Email with subject "%s" was not found in sent messages. Found Subjects: "%s"', $title, join(', ', $sentMessagesTitles)));
    }

    /**
     * @Then At least :number email(s) should be sent
     */
    public function mailShouldBeSent(int $number): void
    {
        $messagesCount = $this->getSentMessagesCount();
        if ($number > $messagesCount) {
            throw new \Exception(sprintf('Only %s email(s) was sent.', $messagesCount));
        }
    }

    /**
     * @Then Exactly :number email(s) should be sent
     */
    public function mailsShouldBeSent(int $number): void
    {
        $messagesCount = $this->getSentMessagesCount();
        if ($number !== $messagesCount) {
            throw new \Exception(sprintf('Number of sent emails (%s) is not equal to expected (%s).', $messagesCount, $number));
        }
    }

    /**
     * @Then No emails should be sent
     */
    public function noEmailsShouldBeSent(): void
    {
        $messagesCount = $this->getSentMessagesCount();
        if (0 !== $messagesCount) {
            throw new \Exception(sprintf('%s email(s) was sent.', $messagesCount));
        }
    }

    private function getSentMessagesCount(): int
    {
        /** @var MessageEvents $events */
        $events = $this->getSymfonyProfile()->getCollector('mailer')->getEvents();

        return count($events->getMessages());
    }

    public function getSymfonyProfile(): Profile
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof SymfonyDriver) {
            throw new UnsupportedDriverActionException('Invalid driver.');
        }

        $profile = $driver->getClient()->getProfile();
        if (false === $profile) {
            throw new \RuntimeException('The profiler is disabled. Activate it by setting '.'framework.profiler.only_exceptions to false in '.'your config');
        }

        return $profile;
    }
}
