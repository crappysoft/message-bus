<?php
declare(strict_types = 1);

namespace App\Repository;

use App\Entity\EmailConfirmationToken;
use App\Exception\NotFoundException;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
interface EmailConfirmationTokenRepositoryInterface
{
    /**
     * @param EmailConfirmationToken $token
     */
    public function add(EmailConfirmationToken $token): void;

    /**
     * @param EmailConfirmationToken $token
     */
    public function remove(EmailConfirmationToken $token): void;

    /**
     * @param ConfirmationToken $token
     *
     * @return EmailConfirmationToken
     *
     * @throws NotFoundException
     */
    public function getByTokenOrThrowException(ConfirmationToken $token): EmailConfirmationToken;

    /**
     * @param Email $email
     *
     * @return EmailConfirmationToken
     *
     * @throws NotFoundException
     */
    public function getByEmailOrThrowException(Email $email): EmailConfirmationToken;
}
