<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @property array userProfiles
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array", nullable=false)
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
     * @var \DateTime
     *
     * @ORM\Column(name="token_limit_date", type="datetime", nullable=true)
     */
    private $tokenLimitDate;

    /**
     * @var array
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="users", cascade={"persist"})
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="TechnicalEvolution", mappedBy="user")
     */
    private $technicalEvolutions;

    /**
     * @ORM\OneToMany(targetEntity="UserTechnicalEvolution", mappedBy="user")
     */
    private $userTechnicalEvolutions;

    /**
     * @ORM\OneToOne(targetEntity="UserProfile",  cascade={"persist"})
     */
    private $userProfile;

    /**
     *
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    protected $compagnies;

    /**
     * @var
     */
    private $username;

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
     * @return mixed
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Set roles
     *
     * @param array $roles
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
     * @return array
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
        $this->compagnies = new \Doctrine\Common\Collections\ArrayCollection();
       /* $this->userProfile = new \Doctrine\Common\Collections\ArrayCollection();*/
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
     * @return array
     */
    public function getCompany()
    {
        return $this->company;
    }



    /**
     * Get compagnies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompagnies()
    {
        return $this->compagnies;
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
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Get plainPassword
     *
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
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
     * @return string
     */
    public function getUserProfile()
    {
        return $this->email;
    }
}
