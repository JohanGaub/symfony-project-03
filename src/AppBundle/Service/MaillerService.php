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
    /** @var \Swift_Mailer */
    private $swiftMailer;

    /** @var Swift_Message */
    private $swiftMessage;

    /** @var string */
    private $subject;

    /** @var string */
    private $sender;

    /** @var array */
    private $recipients = [];

    /** @var mixed */
    private $body;

    /**
     * MailerService constructor.
     * @param Swift_Mailer $swiftMailer
     * @param string $mailer
     */
    public function __construct(Swift_Mailer $swiftMailer, string $mailer)
    {
        $this->swiftMailer  = $swiftMailer;
        $this->swiftMessage = Swift_Message::newInstance();
        $this->sender       = $mailer;
    }

    /**
     * Config email && Send it
     * @param string $subject
     * @param array $recipients
     * @param mixed $body
     * @return array
     */
    public function sendEmail(string $subject, array $recipients, $body)
    {
        $this->subject      = $subject;
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