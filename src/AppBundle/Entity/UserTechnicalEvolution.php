<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserTechnicalEvolution
 *
 * @ORM\Table(name="user_technical_evolution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTechnicalEvolutionRepository")
 */
class UserTechnicalEvolution
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @ORM\Column(name="note", type="integer", nullable=true)
     */
    private $note;

    /**
     * @var string
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="TechnicalEvolution", inversedBy="userTechnicalEvolutions", cascade={"persist"})
     */
    private $technicalEvolution;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userTechnicalEvolutions",cascade={"persist"})
     */
    private $user;

    /**
     * UserTechnicalEvolution constructor.
     *
     * @param $type
     */
    public function __construct($type = 'undefined')
    {
        $this->type = $type;
    }

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
     * Get date
     *
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param DateTime $date
     * @return UserTechnicalEvolution
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get UpdateDate
     *
     * @return DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Set UpdateDate
     *
     * @param DateTime $updateDate
     * @return UserTechnicalEvolution
     */
    public function setUpdateDate(DateTime $updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * Set technicalEvolution
     *
     * @param TechnicalEvolution $technicalEvolution
     *
     * @return UserTechnicalEvolution
     */
    public function setTechnicalEvolution(TechnicalEvolution $technicalEvolution = null)
    {
        $this->technicalEvolution = $technicalEvolution;
        return $this;
    }

    /**
     * Get technicalEvolution
     *
     * @return TechnicalEvolution
     */
    public function getTechnicalEvolution()
    {
        return $this->technicalEvolution;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return UserTechnicalEvolution
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
}
