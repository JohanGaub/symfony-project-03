<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserTechnicalEvolution
 *
 * @ORM\Table(name="user_technical_evolution")
 * @ORM\Entity
 */
class UserTechnicalEvolution
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
     * @var integer
     *
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="TechnicalEvolution", inversedBy="userTechnicalEvolutions")
     */
    private $technicalEvolution;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTechnicalEvolutions")
     */
    private $user;

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
     * Set note
     *
     * @param integer $note
     *
     * @return UserTechnicalEvolution
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return integer
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return UserTechnicalEvolution
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return UserTechnicalEvolution
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
     * Set technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     *
     * @return UserTechnicalEvolution
     */
    public function setTechnicalEvolution(\AppBundle\Entity\TechnicalEvolution $technicalEvolution = null)
    {
        $this->technicalEvolution = $technicalEvolution;

        return $this;
    }

    /**
     * Get technicalEvolution
     *
     * @return \AppBundle\Entity\TechnicalEvolution
     */
    public function getTechnicalEvolution()
    {
        return $this->technicalEvolution;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserTechnicalEvolution
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
