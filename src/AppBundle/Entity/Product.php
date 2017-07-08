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
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="product")
     */
    private $faqs;

    /**
     * @ORM\OneToMany(targetEntity="TechnicalEvolution", mappedBy="product")
     */
    private $technicalEvolutions;

    /**
     * @ORM\OneToMany(targetEntity="Download", mappedBy="product")
     */
    private $downloads;

    /**
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="product")
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Constructor
     */
    public function __construct()
    {
        $this->faqs = new ArrayCollection();
        $this->technicalEvolutions = new ArrayCollection();
        $this->downloads = new ArrayCollection();
    }

    /**
     * Add faq
     *
     * @param Faq $faq
     *
     * @return Product
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
     * Add technicalEvolution
     *
     * @param TechnicalEvolution $technicalEvolution
     *
     * @return Product
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
     * Add download
     *
     * @param Download $download
     *
     * @return Product
     */
    public function addDownload(Download $download)
    {
        $this->downloads[] = $download;

        return $this;
    }

    /**
     * Remove download
     *
     * @param Download $download
     */
    public function removeDownload(Download $download)
    {
        $this->downloads->removeElement($download);
    }

    /**
     * Get downloads
     *
     * @return Collection
     */
    public function getDownloads()
    {
        return $this->downloads;
    }

    public function __toString()
    {
        return $this->getDescription();
    }

    /**
     * Add ticket
     *
     * @param Ticket $ticket
     *
     * @return Product
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
