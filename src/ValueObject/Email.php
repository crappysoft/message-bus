<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Email
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param null|string $email
     */
    public function __construct(?string $email)
    {
        Assertion::notNull($email);
        Assertion::email($email);
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }
}
