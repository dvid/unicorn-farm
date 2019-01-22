<?php

namespace UnicornFarmBundle\Service;

use UnicornFarmBundle\Contract\Mailer;
use UnicornFarmBundle\Entity\User;

class MailService
{
    /** @var  Mailer */
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     * @param $count
     * @return \UnicornFarmBundle\Contract\EmailMessage|null
     * @throws \Exception
     */
    public function sendMail(User $user, $count)
    {
        try {
            $view = 'congratulation';
            $email = $user->getEmail();

            $message = $this->mailer->makeMessage();
            $message->setTo($email);
            $message = $this->mailingHelper->renderMessage($view, [
                'email' => $email,
                'count' => $count,
                'fullName' => $user->getFullName(),
                'en' => true,
                'nl' => true,
            ], $message);
            $this->mailer->send($message);

            return $message;

        } catch (EmailException $e) {
            $this->logger->log('error', $e->getMessage());
            return null;
        }
    }
}