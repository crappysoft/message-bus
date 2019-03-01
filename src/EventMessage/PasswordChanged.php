<?php
declare(strict_types = 1);

namespace App\EventMessage;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class PasswordChanged
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @param string $userId
     */
    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }
}
