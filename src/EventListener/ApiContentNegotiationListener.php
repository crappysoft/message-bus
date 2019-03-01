<?php
declare(strict_types = 1);

namespace App\EventListener;

use App\Controller\API\API;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ApiContentNegotiationListener implements EventSubscriberInterface
{
    private const JSON_CONTENT_TYPE_REGEX = '~^application/json;*~';

    private const HEADER_CONTENT_TYPE = 'content-type';
    private const HEADER_ACCEPT       = 'accept';

    const METHOD_WHITE_LIST = [
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PUT,
        Request::METHOD_DELETE,
    ];

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['checkContentType', 255],
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function checkContentType(GetResponseEvent $event): void
    {
        $request = $event->getRequest();

        $path = $request->getPathInfo();
        if (0 !== strpos($path, API::URL_PREFIX)) {
            return ;
        }

        $method = $request->getMethod();
        if (!in_array($method, self::METHOD_WHITE_LIST)) {
            return ;
        }

        $contentTypeHeaders = (array) $request->headers->get(self::HEADER_CONTENT_TYPE, null, false);
        $acceptHeaders      = (array) $request->headers->get(self::HEADER_ACCEPT, null, false);
        $headers            = array_merge($contentTypeHeaders, $acceptHeaders);

        foreach ($headers as $value) {
            if (preg_match(self::JSON_CONTENT_TYPE_REGEX, $value)) {
                return ;
            }
        }

        $response = new Response(null, Response::HTTP_NOT_FOUND);
        $event->setResponse($response);
    }
}
