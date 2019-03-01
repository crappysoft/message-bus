<?php
declare(strict_types = 1);

namespace App\EventListener;

use App\Controller\API\API;
use App\Response\ApiResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ApiResponseListener implements EventSubscriberInterface
{
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @param NormalizerInterface $normalizer
     */
    public function __construct(NormalizerInterface $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW      => 'convertApiResponseToJsonResponse',
            KernelEvents::EXCEPTION => 'convertExceptionToJsonResponse',
        ];
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function convertExceptionToJsonResponse(GetResponseForExceptionEvent $event): void
    {
        if (0 !== strpos($event->getRequest()->getPathInfo(), API::URL_PREFIX)) {
            return ;
        }

        $exception = $event->getException();
        if ($exception instanceof HttpExceptionInterface) {
            return ;
        }

        $response = ApiResponse::error($exception)->convertToJsonResponse($this->normalizer);
        $event->setResponse($response);
    }

    /**
     * @param GetResponseForControllerResultEvent $event
     */
    public function convertApiResponseToJsonResponse(GetResponseForControllerResultEvent $event): void
    {
        if (0 !== strpos($event->getRequest()->getPathInfo(), API::URL_PREFIX)) {
            return ;
        }

        $data = $event->getControllerResult();
        if (!$data instanceof ApiResponse) {
            return ;
        }

        $response = $data->convertToJsonResponse($this->normalizer);
        $event->setResponse($response);
    }
}
