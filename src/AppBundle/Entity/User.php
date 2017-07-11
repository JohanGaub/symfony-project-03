<?php

namespace AppBundle\Entity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @property array userProfiles
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface, Serializable
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
     * @Assert\Email()
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
     * @var boolean
     * @ORM\Column(name="is_active_by_admin", type="boolean", nullable=false)
     */
    private $isActiveByAdmin;

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
     * @ORM\OneToMany(targetEntity="UserTechnicalEvolution", mappedBy="user", cascade={"remove", "persist"})
     */
    private $userTechnicalEvolutions;

    /**
     * @ORM\OneToOne(targetEntity="UserProfile", cascade={"persist", "remove"})
     */
    private $userProfile;

    /**
     *
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="user")
     */
    private $tickets;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    private $comments;

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
     * @return array The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     *
     * @param array $roles
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
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

        $this->isActive = true;
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
     * Set password
     *
     * @param UserTechnicalEvolution $userTechnicalEvolution
     *
     * @return User
     */
    public function addUserTechnicalEvolution(UserTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions[] = $userTechnicalEvolution;

        return $this;
    }

    /**
     * Remove userTechnicalEvolution
     *
     * @param UserTechnicalEvolution $userTechnicalEvolution
     */
    public function removeUserTechnicalEvolution(UserTechnicalEvolution $userTechnicalEvolution)
    {
        $this->userTechnicalEvolutions->removeElement($userTechnicalEvolution);
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

    /**
     * Add comment
     *
     * @param Comment $comment
     *
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    public function generateToken()
    {
        $today = new \DateTime("now");
        $string = $this->getUsername() . $today->getTimestamp();

        return sha1($string);
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {

    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
        ]);
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->roles,
            ) = unserialize($serialized);
    }

    /**
     * Set isActiveByAdmin
     *
     * @param boolean $isActiveByAdmin
     *
     * @return User
     */
    public function setIsActiveByAdmin($isActiveByAdmin)
    {
        $this->isActiveByAdmin = $isActiveByAdmin;

        return $this;
    }

    /**
     * Get isActiveByAdmin
     *
     * @return boolean
     */
    public function getIsActiveByAdmin()
    {
        return $this->isActiveByAdmin;
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
