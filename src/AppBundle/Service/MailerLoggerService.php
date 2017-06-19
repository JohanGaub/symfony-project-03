<?php
namespace AppBundle\Service;


use Psr\Log\LoggerInterface;
use Swift_Events_SendEvent;

class MailerLoggerService implements \Swift_Events_SendListener
{

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Invoked immediately before the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->logger->info($evt->getMessage()->getSubject() . ' is ready to be sent' );
    }

    /**
     * Invoked immediately after the Message is sent.
     *
     * @param Swift_Events_SendEvent $evt
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $this->logger->info($evt->getMessage()->getSubject() . ' - ' . $evt->getMessage()->getId());
    }
}