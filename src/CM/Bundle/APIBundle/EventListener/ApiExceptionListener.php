<?php

namespace CM\Bundle\APIBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
		if($exception instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
			return false;
		}
        $message = sprintf(
            'My Error says: %s with code: %s',
            $exception->getMessage(),
            $exception->getCode()
        );

        $response = new JsonResponse();
        $response->setData(array(
            'error' => $exception->getCode(),
            'message' => $exception->getMessage()
        ));

        // Customize your response object to display the exception details
        /*$response = new Response();
        $response->setContent($message);*/

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        /*if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }*/

        // Send the modified response object to the event
        $event->setResponse($response);
    }
}