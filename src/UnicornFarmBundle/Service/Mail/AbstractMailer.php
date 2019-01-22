<?php

namespace UnicornFarmBundle\Service\Mail;

use UnicornFarmBundle\Contract\EmailMessage;
use UnicornFarmBundle\Contract\Mailer;

abstract class AbstractMailer implements Mailer
{
    /**
     * Make new instance of SwiftMessage based on whatever $message implementing EmailMessage interface
     *
     * @param EmailMessage $message
     *
     * @return SwiftMessage
     */
    protected function recreateMessage(EmailMessage $message)
    {
        $newMessage = $this->makeMessage()
            ->setBcc($message->getBcc())
            ->setCc($message->getCc())
            ->setFrom($message->getFrom())
            ->setReplyTo($message->getReplyTo())
            ->setSender($message->getSender())
            ->setSubject($message->getSubject())
            ->setTo($message->getTo());

        $body = $message->getBody();
        $message->setBody($body['body'], $body['contentType'], $body['charset']);

        foreach ($message->getParts() as $part) {
            $newMessage->addPart($part['body'], $part['contentType'], $part['charset']);
        }

        return $newMessage;
    }
}
