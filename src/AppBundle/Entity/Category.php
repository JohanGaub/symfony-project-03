<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Documentation;
use AppBundle\Entity\Faq;
use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=255, nullable=false)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="TechnicalEvolution", mappedBy="category")
     */
    private $technicalEvolutions;


    /**
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="category")
     */
    private $faqs;

    /**
     * @ORM\OneToMany(targetEntity="Documentation", mappedBy="category")
     */
    private $documentations;


    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="category")
     */
    private $tickets;




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
     *
     * @return Category
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
     * Set tag
     *
     * @param string $tag
     *
     * @return Category
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Category
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Category
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
     * Constructor
     */
    public function __construct()
    {
        $this->technicalEvolutions = new ArrayCollection();
        $this->faqs = new ArrayCollection();
        $this->documentations = new ArrayCollection();
    }

    /**
     * Add technicalEvolution
     *
     * @param TechnicalEvolution $technicalEvolution
     *
     * @return Category
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
     * Add faq
     *
     * @param Faq $faq
     *
     * @return Category
     */
    public function addFaq(Faq $faq)
    {
        $this->faqs[] = $faq;

        return $this;
    }

    /**
     * Remove faq
     *
     * @param Faq $faq
     */
    public function removeFaq(Faq $faq)
    {
        $this->faqs->removeElement($faq);
    }

    /**
     * Get faqs
     *
     * @return Collection
     */
    public function getFaqs()
    {
        return $this->faqs;
    }

    /**
     * Add documentation
     *
     * @param Documentation $documentation
     *
     * @return Category
     */
    public function addDocumentation(Documentation $documentation)
    {
        $this->documentations[] = $documentation;

        return $this;
    }

    /**
     * Remove documentation
     *
     * @param Documentation $documentation
     */
    public function removeDocumentation(Documentation $documentation)
    {
        $this->documentations->removeElement($documentation);
    }

    /**
     * Get documentations
     *
     * @return Collection
     */
    public function getDocumentations()
    {
        return $this->documentations;
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return Category
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
}
