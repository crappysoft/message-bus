<?php
declare(strict_types = 1);

namespace App\Entity;

use App\Exception\InvalidArgumentException;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use App\ValueObject\Name;
use App\ValueObject\Password;
use App\ValueObject\UserId;
use App\ValueObject\Username;
use App\ValueObject\UserRole;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
final class User implements UserInterface
{
    const SG_LIST    = 'user.list';
    const SG_DETAILS = 'user.details';

    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     *
     * @Groups({User::SG_DETAILS, User::SG_LIST})
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(length=64, unique=true)
     *
     * @Groups({User::SG_DETAILS, User::SG_LIST})
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(length=64, unique=true)
     *
     * @Groups({User::SG_DETAILS})
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="firstName", length=64)
     *
     * @Groups({User::SG_DETAILS, User::SG_LIST})
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", name="lastName", length=64)
     *
     * @Groups({User::SG_DETAILS, User::SG_LIST})
     *
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="salt")
     *
     * @var string
     */
    private $salt;

    /**
     * @ORM\Column(type="boolean", name="is_email_confirmed")
     *
     * @var boolean
     */
    private $isEmailConfirmed;

    /**
     * @param UserId                       $id
     * @param Username                     $username
     * @param Email                        $email
     * @param Name                         $name
     * @param Password                     $password
     * @param UserPasswordEncoderInterface $passwordEncoder
     *
     * @return User
     */
    public static function register(UserId $id, Username $username, Email $email, Name $name, Password $password, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User($id, $username, $email, $name);
        $user->changePassword($password, $passwordEncoder);

        return $user;
    }

    /**
     * @param UserId   $id
     * @param Username $username
     * @param Email    $email
     * @param Name     $name
     */
    public function __construct(UserId $id, Username $username, Email $email, Name $name)
    {
        $this->id               = $id->id();
        $this->username         = $username->username();
        $this->roles            = [UserRole::user()->role()];
        $this->isEmailConfirmed = false;

        $this->changeEmail($email);
        $this->changeName($name);
        $this->createdAt = new \DateTimeImmutable();
        $this->salt = rtrim(str_replace('+', '.', base64_encode(random_bytes(32))), '=');
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * @param Password                     $password
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function changePassword(Password $password, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->password = $passwordEncoder->encodePassword($this, $password->__toString());
    }

    /**
     * @param Name $name
     */
    public function changeName(Name $name): void
    {
        $this->firstName = $name->firstName();
        $this->lastName  = $name->lastName();
    }

    /**
     * @param Email $email
     */
    public function changeEmail(Email $email): void
    {
        $this->email = $email->email();
        $this->isEmailConfirmed = false;
    }

    /**
     * @param EmailConfirmationToken $emailConfirmationToken
     * @param ConfirmationToken      $token
     */
    public function confirmEmail(EmailConfirmationToken $emailConfirmationToken, ConfirmationToken $token)
    {
        if ($this->email !== $emailConfirmationToken->getEmail()
            || $emailConfirmationToken->getToken() !== $token->token()
        ) {
            throw new InvalidArgumentException(sprintf("Wrong token."));
        }

        $this->isEmailConfirmed = true;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::user()->role();

        return array_unique($roles);
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): string
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return boolean
     */
    public function isEmailConfirmed(): bool
    {
        return $this->isEmailConfirmed;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
