<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Username
{
    const MIN_LENGTH = 4;

    /**
     * @var string
     */
    private $username;

    /**
     * @param null|string $username
     */
    public function __construct(?string $username)
    {
        Assertion::notNull($username);
        Assertion::minLength($username, self::MIN_LENGTH);
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->username;
    }
}
