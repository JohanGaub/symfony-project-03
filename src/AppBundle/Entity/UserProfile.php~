<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProfile
 *
 * @ORM\Table(name="user_profile")
 * @ORM\Entity
 */
class UserProfile
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
     * @ORM\Column(name="firstname", type="string", length=255, nullable=false)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=false)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone1", type="string", length=255, nullable=false)
     */
    private $phone1;

    /**
     * @var string
     *
     * @ORM\Column(name="phone2", type="string", length=255, nullable=true)
     */
    private $phone2;



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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return UserProfile
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return UserProfile
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set phone1
     *
     * @param string $phone1
     *
     * @return UserProfile
     */
    public function setPhone1($phone1)
    {
        $this->phone1 = $phone1;

        return $this;
    }

    /**
     * Get phone1
     *
     * @return string
     */
    public function getPhone1()
    {
        return $this->phone1;
    }

    /**
     * Set phone2
     *
     * @param string $phone2
     *
     * @return UserProfile
     */
    public function setPhone2($phone2)
    {
        $this->phone2 = $phone2;

        return $this;
    }

    /**
     * Get phone2
     *
     * @return string
     */
    public function getPhone2()
    {
        return $this->phone2;
    }
}
