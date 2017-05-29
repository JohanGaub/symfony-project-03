<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Company;
use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Entity\Ticket;
use AppBundle\Entity\UserProfile;
use AppBundle\Entity\userTechnicalEvolution;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = array();

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="token_limit_date", type="datetime", nullable=true)
     */
    private $tokenLimitDate;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users", cascade={"persist"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="TechnicalEvolution", mappedBy="user")
     */
    private $technicalEvolutions;

    /**
     * @ORM\OneToMany(targetEntity="userTechnicalEvolution", mappedBy="user")
     */
    private $userTechnicalEvolutions;

    /**
     * @ORM\OneToOne(targetEntity="UserProfile", cascade={"persist"})
     */
    private $userProfile;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="user")
     */
    private $tickets;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Get roles
     *
     * @return mixed
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set tokenLimitDate
     *
     * @param DateTime $tokenLimitDate
     *
     * @return User
     */
    public function setTokenLimitDate($tokenLimitDate)
    {
        $this->tokenLimitDate = $tokenLimitDate;

        return $this;
    }

    /**
     * Get tokenLimitDate
     *
     * @return DateTime
     */
    public function getTokenLimitDate()
    {
        return $this->tokenLimitDate;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->technicalEvolutions = new ArrayCollection();
    }

    /**
     * Set company
     *
     * @param Company $company
     *
     * @return User
     */
    public function setCompany(Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add technicalEvolution
     *
     * @param TechnicalEvolution $technicalEvolution
     *
     * @return User
     */
    public function addTechnicalEvolution(TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions[] = $technicalEvolution;

        return $this;
    }

    /**
     * Remove technicalEvolution
     *
     * @param TechnicalEvolution $technicalEvolution
     */
    public function removeTechnicalEvolution(TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions->removeElement($technicalEvolution);
    }

    /**
     * Get technicalEvolutions
     *
     * @return Collection
     */
    public function getTechnicalEvolutions()
    {
        return $this->technicalEvolutions;
    }

    /**
     * Set userProfile
     *
     * @param UserProfile $userProfile
     *
     * @return User
     */
    public function setUserProfile(UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userProfile
     *
     * @return UserProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Add userTechnicalEvolution
     *
     * @param userTechnicalEvolution $userTechnicalEvolution
     *
     * @return User
     */
    public function addUserTechnicalEvolution(userTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions[] = $userTechnicalEvolution;

        return $this;
    }

    /**
     * Remove userTechnicalEvolution
     *
     * @param userTechnicalEvolution $userTechnicalEvolution
     */
    public function removeUserTechnicalEvolution(userTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions->removeElement($userTechnicalEvolution);
    }

    /**
     * Get userTechnicalEvolutions
     *
     * @return Collection
     */
    public function getUserTechnicalEvolutions()
    {
        return $this->userTechnicalEvolutions;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return User
     */
    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;

        return $this;
    }

    /**
     * Remove ticket
     *
     * @param Ticket $ticket
     */
    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    /**
     * Get tickets
     *
     * @return Collection
     */
    public function getTickets()
    {
        return $this->tickets;
    }
}
