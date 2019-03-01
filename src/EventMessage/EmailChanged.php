<?php
declare(strict_types = 1);

namespace App\EventMessage;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class EmailChanged implements EmailConfirmation
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $oldEmail;

    /**
     * @param string $userId
     * @param string $oldEmail
     */
    public function __construct(string $userId, string $oldEmail)
    {
        $this->userId   = $userId;
        $this->oldEmail = $oldEmail;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getOldEmail(): string
    {
        return $this->oldEmail;
    }
}
