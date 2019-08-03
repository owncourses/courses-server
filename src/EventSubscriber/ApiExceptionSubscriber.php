<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionEvent(ExceptionEvent $event)
    {
        $request = $event->getRequest();
        if ('application/json' === $request->headers->get('Content-Type')) {
            $event->allowCustomResponseCode();
            $event->setResponse(new JsonResponse([
                'success' => false,
                'message' => $event->getException()->getMessage(),
            ], $event->getException()->getStatusCode()));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }
}
