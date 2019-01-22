<?php

namespace UnicornFarmBundle\Service\Mail;

use UnicornFarmBundle\Contract\EmailMessage;
use UnicornFarmBundle\Contract\Mailer;

/**
 * Class SwiftMailer
 *
 * Decorator for Swift Mailer implementing Mailer interface
 *
 * @package UnicornFarmBundle\Contract
 */
class SwiftMailer extends AbstractMailer implements Mailer
{

    /**
     * @var mixed Swift mailer
     */
    protected $swiftMailer;

    /**
     * @var string default from address
     */
    protected $defaultFrom;


    /**
     * SwiftMailer constructor.
     *
     * @param $swiftMailer
     * @param $defaultFrom
     */
    public function __construct($swiftMailer, $defaultFrom)
    {
        $this->swiftMailer = $swiftMailer;
        $this->defaultFrom = $defaultFrom;
    }

    /**
     * Creates implementation of EmailMessage interface
     *
     * @return EmailMessage
     */
    public function makeMessage()
    {
        return new SwiftMessage($this->swiftMailer->createMessage());
    }

    /**
     * @param EmailMessage $message
     *
     * @throws Exception
     */
    public function send(EmailMessage $message)
    {
        // set default from if none set
        if (!$message->getFrom()) {
            $message->setFrom($this->defaultFrom);
        }

        try {
            $this->swiftMailer->send($this->getSwiftMessage($message));
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Allows to get swift mailer instance
     *
     * Important! You should use this only if you are not able to hide dependency on the third-party library
     *
     * @return mixed
     */
    public function getDecoratedMailer()
    {
        return $this->swiftMailer;
    }

    /**
     * Get implementation of Swift_Mime_Message from provided EmailMessage implementation
     *
     * @param EmailMessage $message
     *
     * @return \Swift_Mime_Message
     */
    protected function getSwiftMessage(EmailMessage $message)
    {
        if (!$message instanceof SwiftMessage) {
            $message = $this->recreateMessage($message);
        }

        $swiftMessage = $message->getDecoratedMessage();

        return $swiftMessage;
    }
}
