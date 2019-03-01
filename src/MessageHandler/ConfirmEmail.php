<?php
declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\ConfirmEmailMessage;
use App\Repository\EmailConfirmationTokenRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ConfirmEmail implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @var EmailConfirmationTokenRepositoryInterface
     */
    private $emailConfirmationTokens;

    /**
     * @param UserRepositoryInterface                   $users
     * @param EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens
     */
    public function __construct(UserRepositoryInterface $users, EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens)
    {
        $this->users = $users;
        $this->emailConfirmationTokens = $emailConfirmationTokens;
    }

    /**
     * @param ConfirmEmailMessage $command
     */
    public function __invoke(ConfirmEmailMessage $command): void
    {
        $token = ConfirmationToken::fromString($command->getConfirmationToken());
        $emailConfirmationToken = $this->emailConfirmationTokens->getByTokenOrThrowException($token);

        $user = $this->users->getByEmailOrThrowException(new Email($emailConfirmationToken->getEmail()));
        $user->confirmEmail($emailConfirmationToken, $token);
        $this->emailConfirmationTokens->remove($emailConfirmationToken);
    }
}
