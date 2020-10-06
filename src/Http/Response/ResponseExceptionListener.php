<?php

namespace Oniric85\UsersService\Http\Response;

use Oniric85\UsersService\Exception\Application\ApplicationException;
use Oniric85\UsersService\Http\Request\Exception\InvalidParameterException;
use Oniric85\UsersService\Http\Request\Exception\InvalidPayloadException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ResponseExceptionListener
{
    const PROD_ENVIRONMENT = 'prod';

    private string $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ApplicationException) {
            $statusCode = Response::HTTP_BAD_REQUEST;

            $response = new JsonResponse([
                'error' => $exception->getMessage(),
            ], $statusCode);
        } elseif ($exception instanceof InvalidParameterException) {
            $statusCode = Response::HTTP_BAD_REQUEST;

            $response = new JsonResponse([
                'error' => $exception->getErrors(),
            ], $statusCode);
        } elseif ($exception instanceof InvalidPayloadException) {
            $statusCode = Response::HTTP_BAD_REQUEST;

            $response = new JsonResponse([
                'error' => $exception->getMessage(),
            ], $statusCode);
        } else {
            if (self::PROD_ENVIRONMENT === $this->environment) {
                $response = new JsonResponse([
                    'error' => 'An error occurred with your request.',
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            } else {
                $response = new JsonResponse([
                    'error' => $exception->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        $event->setResponse($response);
        $event->stopPropagation();
    }
}