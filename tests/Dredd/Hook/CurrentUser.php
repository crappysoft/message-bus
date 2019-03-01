<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use App\Entity\User;
use App\Message\RegisterUserMessage;
use App\Repository\UserRepositoryInterface;
use App\ValueObject\UserId;
use Dredd\Hook;
use Dredd\Names;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class CurrentUser extends Hook
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @var JWTManager
     */
    private $jwtManager;

    /**
     * @var UserRepositoryInterface
     */
    private $users;

    /**
     * @var null|User
     */
    private $currentUser;

    /**
     * @param MessageBusInterface     $bus
     * @param JWTManager              $jwtManager
     * @param UserRepositoryInterface $users
     */
    public function __construct(MessageBusInterface $bus, JWTManager $jwtManager, UserRepositoryInterface $users)
    {
        $this->bus = $bus;
        $this->jwtManager = $jwtManager;
        $this->users = $users;
    }

    /**
     * {@inheritDoc}
     */
    public function beforeEach(&$transaction): void
    {
        if ($transaction->expected->statusCode == Response::HTTP_UNAUTHORIZED) {
            return ;
        }

        if (in_array($transaction->request->uri, ['/api/login_check', '/api/register'])
            || in_array($transaction->name, [Names::USER_CHANGE_EMAIL_204, Names::USER_CHANGE_PASSWORD_204])
        ) {
            return ;
        }

        $userId = $this->createUserAndAuthenticate($transaction, 'john.doe', 'john.doe@example.com', 'secret', 'John', 'Doe');
        $this->currentUser = $this->users->getOrThrowException($userId);
    }

    /**
     * {@inheritDoc}
     */
    public function afterEach(&$transaction): void
    {
        $this->currentUser = null;
    }


    /**
     * @param UserId    $userId
     * @param \stdClass $transaction
     */
    public function authenticate(UserId $userId, \stdClass &$transaction): void
    {
        $user = $this->users->getOrThrowException($userId);
        $token = $this->jwtManager->create($user);
        $transaction->request->headers->Authorization = 'Bearer ' . $token;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $firstName
     * @param string $lastName
     *
     * @return UserId
     */
    public function createUser(string $username, string $email, string $password, string $firstName, string $lastName): UserId
    {
        $command = new RegisterUserMessage(
            $username,
            $email,
            $password,
            $firstName,
            $lastName
        );

        $userId = $command->getId();
        $this->bus->dispatch($command);

        return UserId::fromString($userId);
    }

    /**
     * @param \stdClass $transaction
     * @param string    $username
     * @param string    $email
     * @param string    $password
     * @param string    $firstName
     * @param string    $lastName
     *
     * @return UserId
     */
    public function createUserAndAuthenticate(\stdClass &$transaction, string $username, string $email, string $password, string $firstName, string $lastName): UserId
    {
        $userId = $this->createUser($username, $email, $password, $firstName, $lastName);
        $this->authenticate($userId, $transaction);

        return $userId;
    }

    /**
     * @return User|null
     */
    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
}
