<?php
declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;
use App\ValueObject\Password;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangePasswordMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @Assert\NotBlank(message="Current password is required")
     * @Assert\Length(min=Password::MIN_LENGTH, minMessage="Current password should be at least {{ limit }} characters long")
     *
     * @var null|string
     */
    private $currentPassword;

    /**
     * @Assert\NotBlank(message="New password is required")
     * @Assert\Length(min=Password::MIN_LENGTH, minMessage="New password should be at least {{ limit }} characters long")
     *
     * @var null|string
     */
    private $newPassword;

    /**
     * @param string      $userId
     * @param null|string $currentPassword
     * @param null|string $newPassword
     */
    public function __construct(string $userId, ?string $currentPassword = null, ?string $newPassword = null)
    {
        $this->userId          = $userId;
        $this->currentPassword = $currentPassword;
        $this->newPassword     = $newPassword;
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
    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    /**
     * @return null|string
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }
}
