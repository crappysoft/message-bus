<?php
declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class ConfirmEmailMessage implements MessageInterface
{
    /**
     * @Assert\NotBlank()
     *
     * @var null|string
     */
    private $confirmationToken;

    /**
     * @param null|string $confirmationToken
     */
    public function __construct(?string $confirmationToken = null)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return null|string
     */
    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }
}
