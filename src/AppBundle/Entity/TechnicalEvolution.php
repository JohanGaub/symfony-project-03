<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * TechnicalEvolution
 *
 * @ORM\Table(name="technical_evolution")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TechnicalEvolutionRepository")
 */
class TechnicalEvolution
{
    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     * @Assert\Length(
     *      min = 8,
     *      max = 40,
     *      minMessage = "Votre titre doit faire au minimum {{ limit }} caractères ",
     *      maxMessage = "Votre titre doit faire au maximum {{ limit }} caractères "
     * )
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(name="sum_up", type="text", nullable=false)
     * @Assert\Length(
     *      min = 8,
     *      max = 120,
     *      minMessage = "Votre résumé doit faire au minimum {{ limit }} caractères ",
     *      maxMessage = "Votre résumé doit faire au maximum {{ limit }} caractères "
     * )
     */
    private $sumUp;

    /**
     * @var string
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Assert\Length(
     *      min = 120,
     *      max = 120000,
     *      minMessage = "Votre contenu doit faire au minimum {{ limit }} caractères ",
     *      maxMessage = "Votre contenu doit faire au maximum {{ limit }} caractères "
     * )
     */
    private $content;

    /**
     * @var string
     * @ORM\Column(name="reason", type="string", length=255, nullable=false)
     * @Assert\Length(
     *      min = 4,
     *      max = 255,
     *      minMessage = "Votre raison doit faire au minimum {{ limit }} caractères ",
     *      maxMessage = "Votre raison doit faire au maximum {{ limit }} caractères "
     * )
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", cascade={"persist"})
     * @JoinColumn(name="status", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", cascade={"persist"})
     * @JoinColumn(name="origin", referencedColumnName="id")
     */
    private $origin;

    /**
     * @var DateTime
     * @ORM\Column(name="expected_delay", type="datetime", nullable=false)
     */
    private $expectedDelay;

    /**
     * @var DateTime
     * @ORM\Column(name="creation_date", type="datetime", nullable=false)
     */
    private $creationDate;

    /**
     * @var DateTime
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="technicalEvolutions")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="technicalEvolutions")
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="technicalEvolutions", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="UserTechnicalEvolution", mappedBy="technicalEvolution", cascade={"persist"})
     */
    private $userTechnicalEvolutions;

    /**
     * @var boolean
     * @ORM\Column(name="is_archivate", type="boolean")
     */
    private $isArchivate;

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
     * Set title
     *
     * @param string $title
     * @return TechnicalEvolution
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set sumUp
     *
     * @param string $sumUp
     * @return TechnicalEvolution
     */
    public function setSumUp($sumUp)
    {
        $this->sumUp = $sumUp;
        return $this;
    }

    /**
     * Get sumUp
     *
     * @return string
     */
    public function getSumUp()
    {
        return $this->sumUp;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return TechnicalEvolution
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set reason
     *
     * @param string $reason
     * @return TechnicalEvolution
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set status
     *
     * @param Dictionary $status
     * @return TechnicalEvolution
     */
    public function setStatus(Dictionary $status = null)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return Dictionary
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get origin
     *
     * @return Dictionary
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set origin
     *
     * @param mixed $origin
     * @return TechnicalEvolution
     */
    public function setOrigin(Dictionary $origin = null)
    {
        $this->origin = $origin;
        return $this;
    }

    /**
     * Set expectedDelay
     *
     * @param DateTime $expectedDelay
     * @return TechnicalEvolution
     */
    public function setExpectedDelay($expectedDelay)
    {
        $this->expectedDelay = $expectedDelay;
        return $this;
    }

    /**
     * Get expectedDelay
     *
     * @return DateTime
     */
    public function getExpectedDelay()
    {
        return $this->expectedDelay;
    }

    /**
     * Set creationDate
     *
     * @param DateTime $creationDate
     * @return TechnicalEvolution
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * Get creationDate
     *
     * @return DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set updateDate
     *
     * @param DateTime $updateDate
     * @return TechnicalEvolution
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;
        return $this;
    }

    /**
     * Get updateDate
     *
     * @return DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userTechnicalEvolutions = new ArrayCollection();
    }

    /**
     * Set category
     *
     * @param Category $category
     * @return TechnicalEvolution
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Get category
     *
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set product
     *
     * @param Product $product
     * @return TechnicalEvolution
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set user
     *
     * @param User $user
     * @return TechnicalEvolution
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

    /**
     * Add userTechnicalEvolution
     *
     * @param UserTechnicalEvolution $userTechnicalEvolution
     * @return TechnicalEvolution
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
     * Get userTechnicalEvolutions
     *
     * @return Collection
     */
    public function getUserTechnicalEvolutions()
    {
        return $this->userTechnicalEvolutions;
    }

    /**
     * Getter isArchivate
     *
     * @return boolean
     */
    public function getisArchivate()
    {
        return $this->isArchivate;
    }

    /**
     * Setter isArchivate
     *
     * @param boolean $isArchivate
     * @return TechnicalEvolution
     */
    public function setIsArchivate($isArchivate)
    {
        $this->isArchivate = $isArchivate;
        return $this;
    }
}
