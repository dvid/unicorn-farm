<?php

namespace UnicornFarmBundle\Contract;

/**
 * Interface EmailMessage
 *
 * Created to provide portability between different email message implementations
 * Based on \Swift_Mime_Message from SwiftMailer and Zend Mail \Zend\Mail\Message
 *
 * @package UnicornFarmBundle\Contract
 */
interface EmailMessage
{

    /**
     * Set the subject of the message.
     *
     * @param string $subject
     *
     * @return EmailMessage
     */
    public function setSubject($subject);

    /**
     * Get the subject of the message.
     *
     * @return string
     */
    public function getSubject();

    /**
     * Get the body content of this entity.
     *
     * Returns empty array if no body has been set.
     *
     * @return string[] Returned array MUST contain three keys in following format:
     *                  'body' => '', 'contentType' => '', 'charset' => '']
     */
    public function getBody();

    /**
     * Set the body content of this entity as a string.
     *
     * @param string $body
     * @param string $contentType optional, eg.: "text/plain" or "text/html"
     * @param string $charset
     *
     * @return EmailMessage
     */
    public function setBody($body, $contentType = null, $charset = null);

    /**
     * Add a MimePart to this message.
     * You can use this to add additional "text/plain" or "text/html" version of the message.
     *
     * @param string $body
     * @param string $contentType optional, eg.: "text/plain" or "text/html"
     * @param string $charset
     *
     * @return EmailMessage
     */
    public function addPart($body, $contentType = null, $charset = null);

    /**
     * Get additional parts of the message.
     *
     * Returns empty array if the message have no additional parts.
     *
     * @return string[] Returned array MUST contain three keys in the following format:
     *                  ['body' => '', 'contentType' => '', 'charset' => '']
     */
    public function getParts();

    /**
     * Set the sender of this message.
     *
     * If multiple addresses are present in the From field, this SHOULD be set.
     *
     * An associative array (with one element!) can be used to provide a display-
     * name: i.e. array('email@address' => 'Real Name').
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the address.
     *
     * @param mixed  $address
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setSender($address, $name = null);

    /**
     * Get the sender address for this message.
     *
     * This has a higher significance than the From address.
     *
     * @return string
     */
    public function getSender();

    /**
     * Set the From address of this message.
     *
     * It is permissible for multiple From addresses to be set using an array.
     *
     * If multiple From addresses are used, you SHOULD set the Sender address and
     * according to RFC 2822, MUST set the sender address.
     *
     * An array can be used if display names are to be provided: i.e.
     * array('email@address.com' => 'Real Name').
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setFrom($addresses, $name = null);

    /**
     * Get the From address(es) of this message.
     *
     * This method always returns an associative array where the keys are the
     * addresses.
     *
     * @return string[]
     */
    public function getFrom();

    /**
     * Set the Reply-To address(es).
     *
     * Any replies from the receiver will be sent to this address.
     *
     * It is permissible for multiple reply-to addresses to be set using an array.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setReplyTo($addresses, $name = null);

    /**
     * Get the Reply-To addresses for this message.
     *
     * This method always returns an associative array where the keys provide the
     * email addresses.
     *
     * @return string[]
     */
    public function getReplyTo();

    /**
     * Set the To address(es).
     *
     * Recipients set in this field will receive a copy of this message.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setCc()}.
     *
     * If the second parameter is provided and the first is a string, then $name
     * is associated with the address.
     *
     * @param mixed  $addresses
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setTo($addresses, $name = null);

    /**
     * Get the To addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getTo();

    /**
     * Set the Cc address(es).
     *
     * Recipients set in this field will receive a 'carbon-copy' of this message.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * @param mixed  $addresses
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setCc($addresses, $name = null);

    /**
     * Get the Cc addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getCc();

    /**
     * Set the Bcc address(es).
     *
     * Recipients set in this field will receive a 'blind-carbon-copy' of this
     * message.
     *
     * In other words, they will get the message, but any other recipients of the
     * message will have no such knowledge of their receipt of it.
     *
     * This method has the same synopsis as {@link setFrom()} and {@link setTo()}.
     *
     * @param mixed  $addresses
     * @param string $name optional
     *
     * @return EmailMessage
     */
    public function setBcc($addresses, $name = null);

    /**
     * Get the Bcc addresses for this message.
     *
     * This method always returns an associative array, whereby the keys provide
     * the actual email addresses.
     *
     * @return string[]
     */
    public function getBcc();

    /**
     * Get this entire entity in its string form.
     *
     * @return string
     */
    public function toString();
}
