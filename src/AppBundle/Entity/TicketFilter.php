<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\Date;


/**
 * Class TicketFilter
 * @package AppBundle\Entity
 */
class TicketFilter
{
    /**
     * @var int
     *
     */
    private $id;

    /**
     * @var string
     *
     */
    private $subject;

    /**
     * @var string
     *
     */
    private $content;

    /**
     * @var string
     *
     */
    private $origin;

    /**
     * @var string
     *
     */
    private $type;

    /**
     * @var string
     *
     */
    private $emergency;

    /**
     * @var string
     *
     */
    private $status;


    /**
     * @var Date
     *
     */
    private $creationDate;


    /**
     * @var Date
     *
     */
    private $endDate;

    /**
     * @var boolean
     *
     */
    private $isArchive;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Company
     */
    private $company;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id in order to filter by $id
     *
     * @param int $id
     *
     * @return TicketFilter
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return TicketFilter
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return TicketFilter
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set origin
     *
     * @param string $origin
     *
     * @return TicketFilter
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * Get origin
     *
     * @return string
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set emergency
     *
     * @param string $emergency
     *
     * @return TicketFilter
     */
    public function setEmergency($emergency)
    {
        $this->emergency = $emergency;

        return $this;
    }

    /**
     * Get emergency
     *
     * @return string
     */
    public function getEmergency()
    {
        return $this->emergency;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TicketFilter
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set creationDate
     *
     * @param DateTime $creationDate
     *
     * @return TicketFilter
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return Date
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }


    /**
     * Get endDate
     *
     * @return Date
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set endDate
     *
     * @param DateTime $endDate
     *
     * @return TicketFilter
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return TicketFilter
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set isArchive
     *
     * @param boolean $isArchive
     *
     * @return TicketFilter
     */
    public function setIsArchive($isArchive)
    {
        $this->isArchive = $isArchive;

        return $this;
    }

    /**
     * Get isArchive
     *
     * @return boolean
     */
    public function getIsArchive()
    {
        return $this->isArchive;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TicketFilter
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     * @return TicketFilter
     */
    public function setCompany(Company $company)
    {
        $this->company = $company;
        return $this;
    }

}


