<?php
declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;
use App\ValueObject\Name;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ChangeNameMessage implements MessageInterface
{
    /**
     * @Assert\NotBlank(message="userId is required")
     *
     * @var string
     */
    private $userId;

    /**
     * @Assert\NotBlank(message="First name is required")
     * @Assert\Length(
     *     min=Name::MIN_LENGTH,
     *     max=Name::MAX_LENGTH,
     *     minMessage="First name should have {{ limit }} character or more|First name should have {{ limit }} characters or more",
     *     maxMessage="First name should have {{ limit }} character or less|First name should have {{ limit }} characters or less"
     * )
     * @var null|string
     */
    private $firstName;

    /**
     * @Assert\NotBlank(message="Last name is required")
     * @Assert\Length(
     *     min=Name::MIN_LENGTH,
     *     max=Name::MAX_LENGTH,
     *     minMessage="Last name should have {{ limit }} character or more|Last name should have {{ limit }} characters or more",
     *     maxMessage="Last name should have {{ limit }} character or less|Last name should have {{ limit }} characters or less"
     * )
     * @var null|string
     */
    private $lastName;

    /**
     * @param string      $userId
     * @param null|string $firstName
     * @param null|string $lastName
     */
    public function __construct(string $userId, ?string $firstName = null, ?string $lastName = null)
    {
        $this->userId    = $userId;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
