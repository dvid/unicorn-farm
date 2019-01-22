<?php

namespace UnicornFarmBundle\Contract;

/**
 * Interface Mailer
 *
 * Created to provide portability between different mailers implementations
 * Based on \Swift_Mime_Message from SwiftMailer and Zend Mail \Zend\Mail\Message
 *
 * @package UnicornFarmBundle\Contract
 */
interface Mailer
{
    /**
     * Creates implementation of EmailMessage interface
     *
     * @return EmailMessage
     */
    public function makeMessage();

    /**
     * @param EmailMessage $message
     *
     * @return void
     * @throws Exception
     */
    public function send(EmailMessage $message);
}
