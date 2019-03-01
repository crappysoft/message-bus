<?php
declare(strict_types = 1);

namespace App\MessageHandler;

use App\EventMessage\UserRegistered;
use App\Message\RegisterUserMessage;
use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use App\Service\EventRecorder;
use App\ValueObject\Email;
use App\ValueObject\Name;
use App\ValueObject\Password;
use App\ValueObject\UserId;
use App\ValueObject\Username;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class RegisterUser implements MessageHandlerInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepositoryInterface
     */
    private $users;
    /**
     * @var EventRecorder
     */
    private $eventRecorder;

    /**
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepositoryInterface      $users
     * @param EventRecorder                $eventRecorder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepositoryInterface $users, EventRecorder $eventRecorder)
    {
        $this->users           = $users;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventRecorder = $eventRecorder;
    }

    /**
     * @param RegisterUserMessage $command
     *
     * @return void
     */
    public function __invoke(RegisterUserMessage $command): void
    {
        $id       = UserId::fromString($command->getId());
        $username = new Username($command->getUsername());
        $name     = new Name($command->getFirstName(), $command->getLastName());
        $email    = new Email($command->getEmail());
        $password = new Password($command->getPassword());

        $user = User::register($id, $username, $email, $name, $password, $this->passwordEncoder);
        $this->users->add($user);

        $this->eventRecorder->recordEvent(new UserRegistered($id->id()->toString()));
    }
}
