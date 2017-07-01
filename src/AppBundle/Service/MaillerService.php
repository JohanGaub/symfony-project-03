<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class MaillerService
 * @package AppBundle\Service
 */
class MaillerService
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var \Swift_Message
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
     * @param Container $container
     * @param \Swift_Message $swiftMessage
     */
    public function __construct(Container $container, \Swift_Message $swiftMessage)
    {
        $this->container = $container;
        $this->swiftMessage = $swiftMessage::newInstance();
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
    public function emailSend(string $subject, string $sender, array $recipients, $body)
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
                ->setBody($this->body, 'text/html');
            $validity = $this->container->get('mailer')->send($this->swiftMessage);

            if (!isset($data['users']) && !$validity) {
                $data['info']       = false;
                $data['message']    = 'Il y a eu un problème pendant l\'envois de certains emails';
            }
            if (!$validity) {
                $data['users'][] = $email;
            }
        }
        return $data;
    }
}