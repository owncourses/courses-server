<?php

namespace App\EventSubscriber;

use SWP\Component\Common\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function onExceptionEvent(ExceptionEvent $event)
    {
        $request = $event->getRequest();
        $exception = $event->getException();
        if ('application/json' === $request->headers->get('Content-Type')) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            if ($exception instanceof HttpException) {
                $code = $exception->getStatusCode();
            }

            $event->setResponse(new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ], $code));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onExceptionEvent',
        ];
    }
}
