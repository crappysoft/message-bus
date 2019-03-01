<?php
declare(strict_types = 1);

namespace Context\Users;

use App\Message\ChangePasswordMessage;
use App\Message\RegisterUserMessage;
use Assert\Assertion;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangePassword extends AbstractUsersContext
{
    private const USERNAME = 'mike.smith';

    /**
     * @Given registered user with password :password
     */
    public function registeredUserWithEmailAndPassword($password)
    {
        $message = new RegisterUserMessage(self::USERNAME, 'mike.smith@example.com', $password, "Mike", "Smith");
        $this->dispatch($message);

        $userId = $this->getByUsernameOrThrowException(self::USERNAME)->getId()->toString();
        $this->messageBusContext->iFillInWith('userId', $userId);
        $this->getSwiftMailerLogger()->clear();
    }

    /**
     * @Then my password should be changed
     */
    public function myPasswordShouldBeChanged()
    {
        /** @var ChangePasswordMessage $message */
        $message = $this->messageBusContext->getMessage();
        $newPassword = $message->getNewPassword();
        $user = $this->getByUsernameOrThrowException(self::USERNAME);

        /** @var UserPasswordEncoderInterface $passwordEncode */
        $passwordEncoder = $this->get(UserPasswordEncoderInterface::class);
        Assertion::true($passwordEncoder->isPasswordValid($user, $newPassword));
    }

    /**
     * @Then my password :password should not be changed
     */
    public function myPasswordShouldNotBeChanged($password)
    {
        $user = $this->getByUsernameOrThrowException(self::USERNAME);

        /** @var UserPasswordEncoderInterface $passwordEncode */
        $passwordEncoder = $this->get(UserPasswordEncoderInterface::class);
        Assertion::true($passwordEncoder->isPasswordValid($user, $password));
    }

    /**
     * @Then notification email should be sent to me
     */
    public function notificationEmailShouldBeSentToMe()
    {
        $user = $this->getByUsernameOrThrowException(self::USERNAME);
        $email = $user->getEmail();

        $mailLogger = $this->getSwiftMailerLogger();
        Assertion::eq($mailLogger->countMessages(), 1);
        $notificationEmail = $mailLogger->getMessages()[0];
        Assertion::keyExists($notificationEmail->getTo(), $email);
        Assertion::contains($notificationEmail->getBody(), 'Your password was changed');
    }
}
