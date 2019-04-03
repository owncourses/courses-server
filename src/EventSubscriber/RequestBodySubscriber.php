<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestBodySubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ('json' != $request->getContentType() || !$request->getContent()) {
            return;
        }

        $data = \json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE !== \json_last_error()) {
            throw new BadRequestHttpException('invalid json body: '.\json_last_error_msg());
        }

        $request->request->replace(is_array($data) ? $data : array());
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }
}
