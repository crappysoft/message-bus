<?php
declare(strict_types = 1);

namespace App\MessageHandler;

use App\Entity\User;
use App\EventMessage\EmailChanged;
use App\Exception\ForbiddenException;
use App\Message\ChangeEmailMessage;
use App\Repository\UserRepositoryInterface;
use App\Service\EventRecorder;
use App\ValueObject\Email;
use App\ValueObject\Password;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeEmail implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EventRecorder
     */
    private $eventRecorder;

    /**
     * @param UserRepositoryInterface      $users
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EventRecorder                $eventRecorder
     */
    public function __construct(UserRepositoryInterface $users, UserPasswordEncoderInterface $passwordEncoder, EventRecorder $eventRecorder)
    {
        $this->users           = $users;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventRecorder   = $eventRecorder;
    }

    /**
     * @param ChangeEmailMessage $command
     */
    public function __invoke(ChangeEmailMessage $command): void
    {
        $userId = UserId::fromString($command->getUserId());
        $user   = $this->users->getOrThrowException($userId);

        $password = new Password($command->getPassword());
        $newEmail = new Email($command->getNewEmail());
        $oldEmail = $user->getEmail();

        if (!$this->passwordEncoder->isPasswordValid($user, $password->__toString())) {
            throw new ForbiddenException('Wrong password');
        }

        $user->changeEmail($newEmail);
        $this->eventRecorder->recordEvent(new EmailChanged($user->getId()->toString(), $oldEmail));
    }
}
