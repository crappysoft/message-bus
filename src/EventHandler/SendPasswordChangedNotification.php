<?php
declare(strict_types = 1);

namespace App\EventHandler;

use App\EventMessage\PasswordChanged;
use App\Repository\UserRepositoryInterface;
use App\Service\PasswordChangedMailer;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class SendPasswordChangedNotification implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;
    /**
     * @var PasswordChangedMailer
     */
    private $mailer;

    /**
     * @param UserRepositoryInterface $users
     * @param PasswordChangedMailer   $mailer
     */
    public function __construct(UserRepositoryInterface $users, PasswordChangedMailer $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    /**
     * @param PasswordChanged $event
     */
    public function __invoke(PasswordChanged $event): void
    {
        $userId = UserId::fromString($event->getUserId());
        $user = $this->users->getOrThrowException($userId);

        $this->mailer->send($user);
    }
}
