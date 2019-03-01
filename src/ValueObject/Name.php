<?php
declare(strict_types = 1);

namespace App\ValueObject;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class Name
{
    const MIN_LENGTH = 2;
    const MAX_LENGTH = 64;

    /**
     * @ORM\Column(nullable=false)
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(nullable=false)
     *
     * @var string
     */
    private $lastName;

    /**
     * @param null|string $firstName
     * @param null|string $lastName
     */
    public function __construct(?string $firstName, ?string $lastName)
    {
        Assertion::notNull($firstName);
        Assertion::notNull($lastName);
        Assertion::betweenLength($firstName, self::MIN_LENGTH, self::MAX_LENGTH);
        Assertion::betweenLength($lastName, self::MIN_LENGTH, self::MAX_LENGTH);

        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    /**
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }
}
