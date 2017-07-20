<?php

namespace AppBundle\Entity;


class UserFilter
{


    /**
     * @var string
     */
    private $email;


    /**
     * @var boolean
     */
    private $isActiveByAdmin;



    /**
     * @var Company
     */
    private $name;


    /**
     * @var UserProfile
     */
    private $firstname;

    /**
     * @var UserProfile
     */
    private $lastname;


    /**
     * Set firstname
     *
     * @param $firstname
     *
     * @return UserFilter
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return UserProfile
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param $lastname
     *
     * @return UserFilter
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return UserProfile
     */
    public function getlastname()
    {
        return $this->lastname;
    }

    /**
     * Set name
     * @param $name
     * @return UserFilter
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return Company
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param $email
     *
     * @return UserFilter
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
     * Set isActiveByAdmin
     *
     * @param boolean $isActiveByAdmin
     *
     * @return UserFilter
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
}