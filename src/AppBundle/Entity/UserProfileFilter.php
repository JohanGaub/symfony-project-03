<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 13/07/17
 * Time: 14:13
 */

namespace AppBundle\Entity;


class UserProfileFilter
{

    /**
     * @var string
     *
     */
    private $firstname;

    /**
     * @var string
     *
     */
    private $lastname;

    /**
     * @var string
     */
    private $phone;



    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return UserProfileFilter
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
     * @return UserProfileFilter
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
     * @param string $phone
     *
     * @return UserProfileFilter
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * GetFullName (get full user name)
     * Ordered firstname / lastname => true
     * Ordered lastname / firstname => false
     *
     * @param bool $order
     * @return string
     */
    public function getFullName(bool $order = true)
    {
        if ($order)
            return $this->firstname . ' ' . $this->lastname;
        else
            return $this->lastname . ' ' . $this->firstname;
    }
}