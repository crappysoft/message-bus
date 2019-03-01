<?php
declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeEmailMessage implements MessageInterface
{
    /**
     * @Assert\NotBlank(message="userId is required")
     *
     * @var string
     */
    private $userId;

    /**
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email()
     *
     * @var null|string
     */
    private $newEmail;

    /**
     * @Assert\NotBlank(message="Password is required")
     *
     * @var null|string
     */
    private $password;

    /**
     * @param string      $userId
     * @param null|string $newEmail
     * @param null|string $password
     */
    public function __construct(string $userId, ?string $newEmail = null, ?string $password = null)
    {
        $this->userId   = $userId;
        $this->newEmail = $newEmail;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return null|string
     */
    public function getNewEmail(): ?string
    {
        return $this->newEmail;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}
