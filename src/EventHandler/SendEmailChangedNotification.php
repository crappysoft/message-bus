<?php
declare(strict_types = 1);

namespace App\EventHandler;

use App\EventMessage\EmailChanged;
use App\Repository\UserRepositoryInterface;
use App\Service\EmailChangedMailer;
use App\ValueObject\Email;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class SendEmailChangedNotification implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @var EmailChangedMailer
     */
    private $mailer;

    /**
     * @param UserRepositoryInterface $users
     * @param EmailChangedMailer      $mailer
     */
    public function __construct(UserRepositoryInterface $users, EmailChangedMailer $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(EmailChanged $event)
    {
        $oldEmail = new Email($event->getOldEmail());
        $userId   = UserId::fromString($event->getUserId());
        $user     = $this->users->getOrThrowException($userId);

        $this->mailer->send($user, $oldEmail);
    }
}
