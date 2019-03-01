<?php

namespace App\Repository;

use App\Entity\EmailConfirmationToken;
use App\Exception\NotFoundException;
use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
class EmailConfirmationTokenRepository extends ServiceEntityRepository implements EmailConfirmationTokenRepositoryInterface
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EmailConfirmationToken::class);
    }

    /**
     * {@inheritDoc}
     */
    public function add(EmailConfirmationToken $token): void
    {
        $this->_em->persist($token);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(EmailConfirmationToken $token): void
    {
        $this->_em->remove($token);
    }


    /**
     * @param ConfirmationToken $token
     *
     * @return EmailConfirmationToken
     */
    public function getByTokenOrThrowException(ConfirmationToken $token): EmailConfirmationToken
    {
        $result = $this->find($token->token());
        if (null === $result) {
            throw new NotFoundException(sprintf("Email Confirmation Token '%s' does not exist", $token->token()));
        }

        if (!$result instanceof EmailConfirmationToken) {
            throw new \RuntimeException("Unexpected Type");
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function getByEmailOrThrowException(Email $email): EmailConfirmationToken
    {
        $result = $this->findOneBy(['email' => $email->email()]);
        if (null === $result) {
            throw new NotFoundException(sprintf("Email Confirmation Token for '%s' does not exist", $email->email()));
        }

        if (!$result instanceof EmailConfirmationToken) {
            throw new \RuntimeException("Unexpected Type");
        }

        return $result;
    }
}
