<?php
declare(strict_types = 1);

namespace App\MessageHandler;

use App\Entity\User;
use App\EventMessage\PasswordChanged;
use App\Exception\ForbiddenException;
use App\Message\ChangePasswordMessage;
use App\Repository\UserRepositoryInterface;
use App\Service\EventRecorder;
use App\ValueObject\Password;
use App\ValueObject\UserId;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangePassword implements MessageHandlerInterface
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
     * @param ChangePasswordMessage $command
     */
    public function __invoke(ChangePasswordMessage $command): void
    {
        $userId = UserId::fromString($command->getUserId());
        $user   = $this->users->getOrThrowException($userId);

        $newPassword = new Password($command->getNewPassword());
        $oldPassword = new Password($command->getCurrentPassword());

        if (!$this->passwordEncoder->isPasswordValid($user, $oldPassword->__toString())) {
            throw new ForbiddenException('Wrong password');
        }

        $user->changePassword($newPassword, $this->passwordEncoder);
        $this->eventRecorder->recordEvent(new PasswordChanged($user->getId()->toString()));
    }
}
