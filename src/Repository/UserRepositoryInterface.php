<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use App\Exception\NotFoundException;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use App\ValueObject\UserId;
use App\ValueObject\Username;
use Doctrine\Common\Collections\Collection;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
interface UserRepositoryInterface
{
    /**
     * @param UserId $id
     *
     * @return User
     *
     * @throws \App\Exception\NotFoundException In case if user was not found.
     */
    public function getOrThrowException(UserId $id): User;

    /**
     * @param User $user
     *
     * @return void
     */
    public function add(User $user): void;

    /**
     * @param User $user
     */
    public function remove(User $user): void;

    /**
     * @return array
     */
    public function findAll();

    /**
     * @param Username $username
     *
     * @return User
     *
     * @throws \App\Exception\NotFoundException In case if user was not found.
     */
    public function getByUsernameOrThrowException(Username $username): User;

    /**
     * @param Email $email
     *
     * @return User
     */
    public function getByEmailOrThrowException(Email $email): User;
}
