<?php
declare(strict_types = 1);

namespace App\Controller\API;

use App\Entity\User;
use App\Message\ChangeEmailMessage;
use App\Message\ChangeNameMessage;
use App\Message\ChangePasswordMessage;
use App\Message\ConfirmEmailMessage;
use App\Repository\UserRepositoryInterface;
use App\Response\ApiResponse;
use App\ValueObject\UserId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Route("/user")
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class UserController
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param MessageBusInterface   $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @Route("/change-email", methods={Request::METHOD_PUT})
     *
     * @ParamConverter("command", options={"userId": "user.getId()"})
     *
     * @param ChangeEmailMessage $command
     *
     * @return ApiResponse
     */
    public function changeEmailAction(ChangeEmailMessage $command): ApiResponse
    {
        $this->bus->dispatch($command);
        return ApiResponse::success(Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/change-name", methods={Request::METHOD_PUT})
     *
     * @ParamConverter("command", options={"userId": "user.getId()"})
     *
     * @param MessageBusInterface $bus
     * @param ChangeNameMessage   $command
     *
     * @return ApiResponse
     */
    public function changeNameAction(MessageBusInterface $bus, ChangeNameMessage $command): ApiResponse
    {
        $bus->dispatch($command);
        return ApiResponse::success(Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/change-password", methods={Request::METHOD_PUT})
     *
     * @ParamConverter("command", options={"userId": "user.getId()"})
     *
     * @param ChangePasswordMessage $command
     *
     * @return ApiResponse
     */
    public function changePasswordAction(ChangePasswordMessage $command): ApiResponse
    {
        $this->bus->dispatch($command);
        return ApiResponse::success(Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/me")
     *
     * @param TokenStorageInterface $tokenStorage
     *
     * @return ApiResponse
     */
    public function getCurrentAction(TokenStorageInterface $tokenStorage): ApiResponse
    {
        $token = $tokenStorage->getToken();
        if (null === $token) {
            return ApiResponse::error(null, null, Response::HTTP_UNAUTHORIZED);
        }

        $user = $token->getUser();
        return ApiResponse::successWithData($user, [User::SG_DETAILS]);
    }

    /**
     * @Route("", methods={Request::METHOD_GET})
     *
     * @param UserRepositoryInterface $users
     *
     * @return ApiResponse
     */
    public function listAction(UserRepositoryInterface $users): ApiResponse
    {
        $all = $users->findAll();

        $result = [
            'totalPages' => 1,
            'limit' => 10,
            'currentPage' => 1,
            'totalItems' => 1,
            'items' => $all,
        ];

        return ApiResponse::successWithData($result, [User::SG_LIST]);
    }

    /**
     * @Route("/{id}", methods={Request::METHOD_GET}, requirements={"id": ".+"})
     *
     * @param string                  $id
     * @param UserRepositoryInterface $repository
     *
     * @return ApiResponse
     */
    public function getAction(string $id, UserRepositoryInterface $repository): ApiResponse
    {
        $userId = UserId::fromString($id);
        $user   = $repository->getOrThrowException($userId);
        return ApiResponse::successWithData($user, [User::SG_DETAILS]);
    }

    /**
     * @Route("/confirm-email", methods={Request::METHOD_POST})
     *
     * @param ConfirmEmailMessage $message
     *
     * @return ApiResponse
     */
    public function confirmEmailAction(ConfirmEmailMessage $message): ApiResponse
    {
        $this->bus->dispatch($message);
        return ApiResponse::success(Response::HTTP_NO_CONTENT);
    }
}
