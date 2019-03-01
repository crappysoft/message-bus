<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use App\Message\RegisterUserMessage;
use Dredd\Hook;
use Dredd\Names;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Auth extends Hook
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @param CurrentUser $currentUser
     */
    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * {@inheritDoc}
     */
    public function before(): array
    {
        return [
            Names::LOGIN_CHECK_POST_200 => function (&$transaction): void {
                $payload = json_decode($transaction->request->body, true);
                $this->currentUser->createUser($payload['username'], 'john.doe@example.com', $payload['password'], 'John', 'Doe');
            },
        ];
    }
}
