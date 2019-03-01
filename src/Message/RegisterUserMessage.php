<?php
declare(strict_types = 1);

namespace App\Message;

use App\ValueObject\UserId;
use Symfony\Component\Validator\Constraints as Assert;
use App\ValueObject\Name;
use App\ValueObject\Password;
use App\ValueObject\Username;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class RegisterUserMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Username is required")
     * @Assert\Length(min=Username::MIN_LENGTH, minMessage="Username should be at least {{ limit }} characters long")
     *
     * @var null|string
     */
    private $username;

    /**
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email()
     *
     * @var null|string
     */
    private $email;

    /**
     * @Assert\NotBlank(message="First name is required")
     * @Assert\Length(
     *     min=Name::MIN_LENGTH,
     *     max=Name::MAX_LENGTH,
     *     minMessage="First name should have {{ limit }} character or more|First name should have {{ limit }} characters or more",
     *     maxMessage="First name should have {{ limit }} character or less|First name should have {{ limit }} characters or less"
     * )
     *
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
     *
     * @var null|string
     */
    private $lastName;

    /**
     * @Assert\NotBlank(message="Password is required")
     * @Assert\Length(min=Password::MIN_LENGTH, minMessage="Password should be at least {{ limit }} characters long")
     *
     * @var null|string
     */
    private $password;

    /**
     * @param null|string $username
     * @param null|string $email
     * @param null|string $password
     * @param null|string $firstName
     * @param null|string $lastName
     */
    public function __construct(
        ?string $username = null,
        ?string $email = null,
        ?string $password = null,
        ?string $firstName = null,
        ?string $lastName = null
    ) {
        $this->id        = UserId::generate()->id()->toString();
        $this->username  = $username;
        $this->email     = $email;
        $this->password  = $password;
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
