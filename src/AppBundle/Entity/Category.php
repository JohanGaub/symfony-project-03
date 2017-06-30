<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
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
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="TechnicalEvolution", mappedBy="category")
     */
    private $technicalEvolutions;

    /**
     * @ORM\OneToMany(targetEntity="Faq", mappedBy="category")
     */
    private $faqs;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Download", mappedBy="category")
     */
    private $downloads;

    /**
     * @ORM\ManyToOne(targetEntity="Dictionary", cascade={"persist"})
     * @JoinColumn(name="type", referencedColumnName="id")
     */
    private $type;


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
     * Constructor
     */
    public function __construct()
    {
        $this->technicalEvolutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->downloads = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     *
     * @return Category
     */
    public function addTechnicalEvolution(\AppBundle\Entity\TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions[] = $technicalEvolution;

        return $this;
    }

    /**
     * Remove technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     */
    public function removeTechnicalEvolution(\AppBundle\Entity\TechnicalEvolution $technicalEvolution)
    {
        $this->technicalEvolutions->removeElement($technicalEvolution);
    }

    /**
     * Get technicalEvolutions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTechnicalEvolutions()
    {
        return $this->technicalEvolutions;
    }

    /**
     * Add faq
     *
     * @param \AppBundle\Entity\Faq $faq
     *
     * @return Category
     */
    public function addFaq(\AppBundle\Entity\Faq $faq)
    {
        $this->faqs[] = $faq;

        return $this;
    }

    /**
     * Remove faq
     *
     * @param \AppBundle\Entity\Faq $faq
     */
    public function removeFaq(\AppBundle\Entity\Faq $faq)
    {
        $this->faqs->removeElement($faq);
    }

    /**
     * Get faqs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFaqs()
    {
        return $this->faqs;
    }

    /**
     * Add download
     *
     * @param \AppBundle\Entity\Download $download
     *
     * @return Category
     */
    public function addDownload(\AppBundle\Entity\Download $download)
    {
        $this->downloads[] = $download;

        return $this;
    }

    /**
     * Remove documentation
     *
     * @param \AppBundle\Entity\Download $download
     */
    public function removeDownload(\AppBundle\Entity\Download $download)
    {
        $this->downloads->removeElement($download);
    }

    /**
     * Get documentations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentations()
    {
        return $this->downloads;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param \AppBundle\Entity\Dictionary $type
     *
     * @return Category
     */
    public function setType(\AppBundle\Entity\Dictionary $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \AppBundle\Entity\Dictionary
     */
    public function getType()
    {
        return $this->type;
    }
}
