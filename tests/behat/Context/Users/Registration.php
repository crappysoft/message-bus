<?php
declare(strict_types = 1);

namespace Context\Users;

use App\Message\RegisterUserMessage;
use App\Repository\EmailConfirmationTokenRepositoryInterface;
use App\ValueObject\Email;
use App\ValueObject\Username;
use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Registration extends AbstractUsersContext
{
    /**
     * @Then I should have been registered
     */
    public function iShouldHaveBeenRegistered()
    {
        /** @var RegisterUserMessage $message */
        $message  = $this->messageBusContext->getMessage();
        $username = new Username($message->getUsername());

        $this->em()->clear();
        $this->users()->getByUsernameOrThrowException($username);
    }

    /**
     * @Then email which contains confirmation token should be sent to me
     */
    public function emailWhichContainsConfirmationTokenShouldBeSentToMe()
    {
        $this->em()->clear();

        /** @var RegisterUserMessage $message */
        $message  = $this->messageBusContext->getMessage();
        $username = new Username($message->getUsername());

        $user  = $this->users()->getByUsernameOrThrowException($username);
        $email = $user->getEmail();

        /** @var EmailConfirmationTokenRepositoryInterface $emailConfirmationTokens */
        $emailConfirmationTokens = $this->get(EmailConfirmationTokenRepositoryInterface::class);
        $token = $emailConfirmationTokens->getByEmailOrThrowException(new Email($email));

        $swiftLogger = $this->getSwiftMailerLogger();
        Assertion::eq($swiftLogger->countMessages(), 1);

        $collectedMessages = $swiftLogger->getMessages();
        $swiftMessage      = $collectedMessages[0];
        Assertion::keyExists($swiftMessage->getTo(), $token->getEmail());
        Assertion::keyExists($swiftMessage->getTo(), $user->getEmail());
        Assertion::contains($swiftMessage->getBody(), $token->getToken());
    }
}
