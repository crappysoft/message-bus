<?php
declare(strict_types = 1);

namespace Context\Users;

use App\Message\ChangeEmailMessage;
use App\Message\RegisterUserMessage;
use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeEmail extends AbstractUsersContext
{
    private const USERNAME = 'john.galt';

    /**
     * @Given registered user with :email email and :password password
     */
    public function registeredUserWithEmailAndPassword($email, $password)
    {
        $message = new RegisterUserMessage(self::USERNAME, $email, $password, "John", "Galt");
        $this->dispatch($message);

        $userId = $this->getByUsernameOrThrowException(self::USERNAME)->getId()->toString();
        $this->messageBusContext->iFillInWith('userId', $userId);
    }

    /**
     * @Then my email should have been changed
     */
    public function myEmailShouldHaveBeenChanged()
    {
        $user = $this->getByUsernameOrThrowException(self::USERNAME);
        /** @var ChangeEmailMessage $message */
        $message = $this->messageBusContext->getMessage();

        Assertion::eq($message->getNewEmail(), $user->getEmail());
    }

    /**
     * @Then my email :email should not be changed
     */
    public function myEmailShouldNotBeChanged($email)
    {
        $user = $this->getByUsernameOrThrowException(self::USERNAME);
        Assertion::eq($email, $user->getEmail());
    }
}
