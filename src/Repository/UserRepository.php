<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\NotFoundException;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use App\ValueObject\UserId;
use App\ValueObject\Username;
use Assert\Assertion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrThrowException(UserId $id): User
    {
        $user = $this->find($id->id());

        Assertion::notNull($user, sprintf('User with id "%s" was not found', $id->id()->toString()));
        Assertion::isInstanceOf($user, User::class);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user): void
    {
        $this->_em->persist($user);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(User $user): void
    {
        $this->_em->remove($user);
    }

    /**
     * {@inheritDoc}
     */
    public function getByUsernameOrThrowException(Username $username): User
    {
        $user = $this->findOneBy(['username' => $username->username()]);
        Assertion::notNull($user, sprintf('User with username "%s" was not found', $username->username()));
        Assertion::isInstanceOf($user, User::class);

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function getByEmailOrThrowException(Email $email): User
    {
        $user = $this->findOneBy(['email' => $email->email()]);
        if (null === $user) {
            throw new NotFoundException(sprintf("User with email '%s' does not exist", $email->email()));
        }

        if (!$user instanceof User) {
            throw new \RuntimeException("Unexpected Type");
        }

        return $user;
    }
}
