<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\User;
use App\ValueObject\Email;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class PasswordChangedMailer
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
     * @param User $user
     */
    public function send(User $user): void
    {
        $message = new \Swift_Message('Awesome App: Password Changed');
        $message->setFrom('no-reply@localhost');
        $message->setTo($user->getEmail());
        $message->setBody('Your password was changed. If you did\'t do it, please contact us');

        $this->mailer->send($message);
    }
}
