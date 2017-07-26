<?php

namespace AppBundle\Entity;

use AppBundle\Repository\DictionaryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketRepository")
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="text")
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="emergency", type="string", length=255)
     */
    private $emergency;


    /**
     * @var string
     *
     * @ORM\Column(name="upload", type="string", nullable=true)
     * @Assert\File(
     *     mimeTypes = {"image/jpg", "image/jpeg", "image/gif", "image/png", "application/txt", "application/pdf", "application/doc", "application/odt"},
     *     mimeTypesMessage = "Vous pouvez uploader des fichiers images ou txt. Si vous souhaitez uploader d'autres types de fichier, adressez-vous à l'administrateur",
     *     maxSizeMessage = "Votre fichier est trop volumineux. Veuillez vous adressez à l'administrateur pour plus d'informations.",
     *     maxSize = "3M"
     * )
     */
    private $upload;


    /**
     * @var DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime")
     * @Assert\DateTime()
     */
    private $creationDate;


    /**
     * @var DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $updateDate;


    /**
     * @var DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $endDate;


    /**
     * @var boolean
     *
     * @ORM\Column(name="is_archive", type="boolean", nullable=false)
     */
    private $isArchive;


    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="tickets", cascade={"persist"})
     */
    private $category;


    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tickets", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="tickets", cascade={"persist"})
     */
    private $product;


    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="ticket")
     */
    private $comments;


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
     * @ORM\ManyToOne(targetEntity="Dictionary", cascade={"persist"})
     * @JoinColumn(name="ticket_type", referencedColumnName="id")
     */
    private $ticketType;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->creationDate = new \DateTime('NOW');
        $this->isArchive = false;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Ticket
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }


    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }


    /**
     * Set content
     *
     * @param string $content
     *
     * @return Ticket
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
     * Set emergency
     *
     * @param string $emergency
     *
     * @return Ticket
     */
    public function setEmergency($emergency)
    {
        $this->emergency = $emergency;

        return $this;
    }


    /**
     * Get emergency
     *
     * @return string
     */
    public function getEmergency()
    {
        return $this->emergency;
    }


    /**
     * Set upload
     *
     * @param string $upload
     *
     * @return Ticket
     */
    public function setUpload($upload)
    {
        $this->upload = $upload;

        return $this;
    }


    /**
     * Get upload
     *
     * @return string
     */
    public function getUpload()
    {
        return $this->upload;
    }


    /**
     * Set creationDate
     *
     * @param DateTime $creationDate
     *
     * @return Ticket
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
     *
     * @return Ticket
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
     * Set endDate
     *
     * @param DateTime $endDate
     *
     * @return Ticket
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }


    /**
     * Get endDate
     *
     * @return DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }


    /**
     * Set category
     *
     * @param Category $category
     *
     * @return Ticket
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
     * Set user
     *
     * @param User $user
     *
     * @return Ticket
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
     * Set product
     *
     * @param Product $product
     *
     * @return Ticket
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
     * Add comment
     *
     * @param Comment $comment
     *
     * @return Ticket
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
     * @return Collection
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * Set isArchive
     *
     * @param boolean $isArchive
     *
     * @return Ticket
     */
    public function setIsArchive($isArchive)
    {
        $this->isArchive = $isArchive;

        return $this;
    }


    /**
     * Get isArchive
     *
     * @return boolean
     */
    public function getIsArchive()
    {
        return $this->isArchive;
    }


    /**
     * Set status
     *
     * @param Dictionary $status
     *
     * @return Ticket
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
     * Set origin
     *
     * @param mixed $origin
     *
     * @return Ticket
     */
    public function setOrigin(Dictionary $origin = null)
    {
        $this->origin = $origin;

        return $this;
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
     * @return mixed
     */
    public function getTicketType()
    {
        return $this->ticketType;
    }

    /**
     * @param mixed $ticketType
     * @return Ticket
     */
    public function setTicketType($ticketType)
    {
        $this->ticketType = $ticketType;
        return $this;
    }
}
