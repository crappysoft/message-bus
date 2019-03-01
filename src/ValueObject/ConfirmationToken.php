<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Assert\Assertion;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ConfirmationToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @param null|string $token
     *
     * @return ConfirmationToken
     */
    public static function fromString(?string $token)
    {
        return new self($token);
    }

    /**
     * @return ConfirmationToken
     */
    public static function generate(): ConfirmationToken
    {
        $token = rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
        return new self($token);
    }

    /**
     * @param string|null $token
     */
    private function __construct(?string $token)
    {
        Assertion::notNull($token);
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }
}
