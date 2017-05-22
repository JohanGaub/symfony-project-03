<?php

namespace AppBundle\Entity;

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
     * @var string
     *
     * @ORM\Column(name="roles", type="text", nullable=false)
     */
    private $roles;

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
     * @var \DateTime
     *
     * @ORM\Column(name="token_limit_date", type="datetime", nullable=true)
     */
    private $tokenLimitDate;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users")
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
     * @ORM\OneToOne(targetEntity="UserProfile")
     */
    private $userProfile;

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
     * Set roles
     *
     * @param string $roles
     *
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */
    public function getRoles()
    {
        return $this->roles;
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
     * @param \DateTime $tokenLimitDate
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
     * @return \DateTime
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
        $this->technicalEvolutions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return User
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     *
     * @return User
     */
    public function addTechnicalEvolution(\AppBundle\Entity\TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions[] = $technicalEvolution;

        return $this;
    }

    /**
     * Remove technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     */
    public function removeTechnicalEvolution(\AppBundle\Entity\TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions->removeElement($technicalEvolution);
    }

    /**
     * Get technicalEvolutions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTechnicalEvolutions()
    {
        return $this->technicalEvolutions;
    }

    /**
     * Set userProfile
     *
     * @param \AppBundle\Entity\UserProfile $userProfile
     *
     * @return User
     */
    public function setUserProfile(\AppBundle\Entity\UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userProfile
     *
     * @return \AppBundle\Entity\UserProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Add userTechnicalEvolution
     *
     * @param \AppBundle\Entity\userTechnicalEvolution $userTechnicalEvolution
     *
     * @return User
     */
    public function addUserTechnicalEvolution(\AppBundle\Entity\userTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions[] = $userTechnicalEvolution;

        return $this;
    }

    /**
     * Remove userTechnicalEvolution
     *
     * @param \AppBundle\Entity\userTechnicalEvolution $userTechnicalEvolution
     */
    public function removeUserTechnicalEvolution(\AppBundle\Entity\userTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions->removeElement($userTechnicalEvolution);
    }

    /**
     * Get userTechnicalEvolutions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserTechnicalEvolutions()
    {
        return $this->userTechnicalEvolutions;
    }
}