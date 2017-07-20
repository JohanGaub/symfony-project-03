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

    /** @var Dictionary */
    private $origin;

    /**
     * @var string
     *
     */
    private $emergency;

    /** @var Dictionary */
    private $status;

    /** @var Dictionary */
    private $ticketType;

    /** @var Dictionary */
    private $categoryType;

    /** @var Category */
    private $category;

    /**
     * @var DateTime
     *
     */
    private $creationDate;

    /**
     * @var DateTime
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
     * @param $origin
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
     * @return Dictionary
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
     * @return Dictionary
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set ticket_type
     *
     * @param string $ticketType
     *
     * @return TicketFilter
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;

        return $this;
    }

    /**
     * Get ticket_type
     *
     * @return Dictionary
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * @return Dictionary
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @param $categoryType
     * @return TicketFilter
     */
    public function setCategoryType($categoryType)
    {
        $this->categoryType = $categoryType;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return TicketFilter
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
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
     * @return DateTime|Date
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Get endDate
     *
     * @return DateTime|Date
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
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param $company
     * @return TicketFilter
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return [
            'id'              =>  $this->getId(),
            'company'         =>  $this->getCompany(),
            'emergency'       =>  $this->getEmergency(),
            'origin'          =>  $this->getOrigin(),
            'subject'         =>  $this->getSubject(),
            'status'          =>  $this->getStatus(),
            'creationDate'    =>  $this->getCreationDate(),
            'endDate'         =>  $this->getEndDate(),
        ];
    }
}


