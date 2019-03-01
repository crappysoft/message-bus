<?php
declare(strict_types = 1);

namespace Dredd\Hook;

use App\Repository\EmailConfirmationTokenRepositoryInterface;
use App\ValueObject\Email;
use Dredd\Hook;
use Dredd\Names;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class User extends Hook
{
    /**
     * @var EmailConfirmationTokenRepositoryInterface
     */
    private $emailConfirmationTokens;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @param CurrentUser                               $currentUser
     * @param EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens
     */
    public function __construct(CurrentUser $currentUser, EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens)
    {
        $this->emailConfirmationTokens = $emailConfirmationTokens;
        $this->currentUser = $currentUser;
    }

    /**
     * {@inheritDoc}
     */
    public function before(): array
    {
        return [
            Names::USER_CHANGE_PASSWORD_204 => function (&$transaction): void {
                $payload = json_decode($transaction->request->body, true);
                $this->currentUser->createUserAndAuthenticate($transaction, 'john.doe', 'john.doe@example.com', $payload['currentPassword'], 'John', 'Doe');
            },
            Names::USER_CHANGE_EMAIL_204 => function (&$transaction): void {
                $payload = json_decode($transaction->request->body, true);
                $this->currentUser->createUserAndAuthenticate($transaction, 'john.doe', 'john.doe@example.com', $payload['password'], 'John', 'Doe');
            },
            Names::USER_CONFIRM_EMAIL_204 => function (&$transaction): void {
                $user = $this->currentUser->getCurrentUser();
                $token = $this->emailConfirmationTokens->getByEmailOrThrowException(new Email($user->getEmail()));
                $transaction->request->body = json_encode(['confirmationToken' => $token->getToken()]);
            },
        ];
    }
}
