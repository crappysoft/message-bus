<?php
declare(strict_types = 1);

namespace App\EventHandler;

use App\Entity\EmailConfirmationToken;
use App\EventMessage\EmailConfirmation;
use App\Repository\EmailConfirmationTokenRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Service\EmailConfirmationMailer;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class SendConfirmationEmail implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @var EmailConfirmationMailer
     */
    private $mailer;

    /**
     * @var EmailConfirmationTokenRepositoryInterface
     */
    private $emailConfirmationTokens;

    /**
     * @param UserRepositoryInterface                   $users
     * @param EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens
     * @param EmailConfirmationMailer                   $mailer
     */
    public function __construct(UserRepositoryInterface $users, EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens, EmailConfirmationMailer $mailer)
    {
        $this->users  = $users;
        $this->mailer = $mailer;
        $this->emailConfirmationTokens = $emailConfirmationTokens;
    }

    /**
     * @param EmailConfirmation $event
     */
    public function __invoke(EmailConfirmation $event): void
    {
        $userId = UserId::fromString($event->getUserId());
        $user   = $this->users->getOrThrowException($userId);

        $emailConfirmationToken = new EmailConfirmationToken(ConfirmationToken::generate(), new Email($user->getEmail()));
        $this->emailConfirmationTokens->add($emailConfirmationToken);
        $this->mailer->send($user, $emailConfirmationToken);
    }
}
