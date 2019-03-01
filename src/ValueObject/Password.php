<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Password
{
    const MIN_LENGTH = 6;

    /**
     * @var string
     */
    private $password;

    /**
     * @param null|string $password
     */
    public function __construct(?string $password)
    {
        Assertion::notNull($password);
        Assertion::minLength($password, self::MIN_LENGTH);
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}
