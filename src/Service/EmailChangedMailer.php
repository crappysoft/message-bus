<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use App\ValueObject\Email;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class EmailChangedMailer
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
     * @param User  $user
     * @param Email $oldEmail
     */
    public function send(User $user, Email $oldEmail): void
    {
        $message = new \Swift_Message('Awesome App: Email Changed');
        $message->setFrom('no-reply@localhost');
        $message->setTo($oldEmail->email());
        $message->setBody(sprintf('Your email was changed to "%s". If you did\'t do it, please contact us', $user->getEmail()));

        $this->mailer->send($message);
    }
}
