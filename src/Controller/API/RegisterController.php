<?php
declare(strict_types = 1);

namespace App\Controller\API;

use App\Message\RegisterUserMessage;
use App\Response\ApiResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class RegisterController
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/register", methods={Request::METHOD_POST})
     *
     * @param RegisterUserMessage $command
     *
     * @return ApiResponse
     */
    public function registerAction(RegisterUserMessage $command): ApiResponse
    {
        $this->bus->dispatch($command);
        return ApiResponse::success(Response::HTTP_CREATED);
    }
}
