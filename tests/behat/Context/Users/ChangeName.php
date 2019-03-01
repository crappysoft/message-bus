<?php
declare(strict_types = 1);

namespace Context\Users;

use App\Entity\User;
use App\Message\ChangeNameMessage;
use App\Message\RegisterUserMessage;
use App\ValueObject\Username;
use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeName extends AbstractUsersContext
{
    private const USERNAME = 'jane.smith';

    /**
     * @Given registered user named :firstName :lastName
     */
    public function registeredUserWithUsernameNamed($firstName, $lastName)
    {
        $message = new RegisterUserMessage(self::USERNAME, 'jane.smith@example.com', '123qwe', $firstName, $lastName);
        $this->dispatch($message);

        $userId = $this->getByUsernameOrThrowException(self::USERNAME)->getId()->toString();;
        $this->messageBusContext->iFillInWith('userId', $userId);
    }

    /**
     * @Then my name should have been changed
     */
    public function myNameShouldHaveBeenChanged()
    {
        $user = $this->getByUsernameOrThrowException(self::USERNAME);
        /** @var ChangeNameMessage $message */
        $message = $this->messageBusContext->getMessage();

        Assertion::eq($message->getFirstName(), $user->getFirstName());
        Assertion::eq($message->getLastName(), $user->getLastName());
    }
}
