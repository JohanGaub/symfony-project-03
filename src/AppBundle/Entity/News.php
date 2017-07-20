<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use \DateTime;

/**
 * News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NewsRepository")
 */
class News
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @var DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var boolean
     * @ORM\Column(name="is_visible", type="boolean")
     */
    private $isVisible;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", cascade={"persist"})
     * @JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return News
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return News
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return News
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     * @return News
     */
    public function setCreationDate(DateTime $creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * @param DateTime $updateDate
     * @return News
     */
    public function setUpdateDate(DateTime $updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param bool $isVisible
     * @return News
     */
    public function setIsVisible(bool $isVisible)
    {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return News
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

}