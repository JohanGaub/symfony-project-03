<?php

namespace AppBundle\Service;

use Swift_Mailer;
use Swift_Message;

/**
 * Class MaillerService
 * @package AppBundle\Service
 */
class MaillerService
{
    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;

    /**
     * @var Swift_Message
     */
    private $swiftMessage;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var array
     */
    private $recipients = [];

    /**
     * @var mixed
     */
    private $body;

    /**
     * MaillerService constructor.
     * @param Swift_Mailer $swiftMailer
     */
    public function __construct(Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
        $this->swiftMessage = Swift_Message::newInstance();
    }

    /**
     * Config email && Send it
     *
     * @param string $subject
     * @param string $sender
     * @param array $recipients
     * @param mixed $body
     * @return array
     */
    public function sendEmail(string $subject, string $sender, array $recipients, $body)
    {
        $this->subject      = $subject;
        $this->sender       = $sender;
        $this->recipients   = $recipients;
        $this->body         = $body;

        $data = [
            'info'      => true,
            'message'   => 'Le/les email(s) ont été envoyés avec succès'
        ];

        foreach ($this->recipients as $user) {
            $email = $user->getEmail();
            $this->swiftMessage
                    ->setSubject($this->subject)
                    ->setFrom($this->sender)
                    ->setTo($email)
                    ->setBody($body, 'text/html');
            $this->swiftMailer->send($this->swiftMessage);
        }
        return $data;
    }

}