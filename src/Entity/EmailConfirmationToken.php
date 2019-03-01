<?php

namespace App\Entity;

use App\ValueObject\ConfirmationToken;
use App\ValueObject\Email;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmailConfirmationTokenRepository")
 */
class EmailConfirmationToken
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private $token;

    /**
     * @ORM\Column(type="string", name="email", length=255, nullable=false)
     *
     * @var string
     */
    private $email;

    /**
     * @param ConfirmationToken $token
     * @param Email             $email
     */
    public function __construct(ConfirmationToken $token, Email $email)
    {
        $this->token = $token->token();
        $this->email = $email->email();
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
