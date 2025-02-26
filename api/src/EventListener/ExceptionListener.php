<?php

namespace App\EventListener;

use App\Service\Error;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ExceptionListener
{
    #[AsEventListener(event: KernelEvents::EXCEPTION)]
    public function onKernelException(ExceptionEvent $event): void
    {
        // get the exception
        $exception = $event->getThrowable();
        // handle it
        $error = $exception instanceof HttpException ? (new Error($exception->getMessage(), $exception->getStatusCode()))->getObject() : (new Error($exception->getMessage()))->getObject();
        $event->setResponse(new JsonResponse($error));
    }
}
