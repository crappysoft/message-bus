<?php
declare(strict_types = 1);

namespace App\MessageHandler;

use App\Entity\User;
use App\Exception\ForbiddenException;
use App\Message\ChangeNameMessage;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\Name;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeName implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @param UserRepositoryInterface $users
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }

    /**
     * @param ChangeNameMessage $command
     */
    public function __invoke(ChangeNameMessage $command): void
    {
        $userId = UserId::fromString($command->getUserId());
        $user   = $this->users->getOrThrowException($userId);

        $name = new Name($command->getFirstName(), $command->getLastName());
        $user->changeName($name);
    }
}
