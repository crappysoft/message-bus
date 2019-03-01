<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\EmailConfirmationToken;
use App\Entity\User;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class EmailConfirmationMailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @param \Swift_Mailer $mailer
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User                   $user
     * @param EmailConfirmationToken $token
     */
    public function send(User $user, EmailConfirmationToken $token): void
    {
        $message = new \Swift_Message('Awesome App: Email Confirmation');
        $message->setFrom('no-reply@localhost');
        $message->setTo($token->getEmail());
        $message->setBody($token->getToken());

        $this->mailer->send($message);
    }
}
