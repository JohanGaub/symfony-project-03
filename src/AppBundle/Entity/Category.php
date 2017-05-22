<?php

namespace AppBundle\Entity;

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
        $this->technicalEvolutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentations = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add documentation
     *
     * @param \AppBundle\Entity\Documentation $documentation
     *
     * @return Category
     */
    public function addDocumentation(\AppBundle\Entity\Documentation $documentation)
    {
        $this->documentations[] = $documentation;

        return $this;
    }

    /**
     * Remove documentation
     *
     * @param \AppBundle\Entity\Documentation $documentation
     */
    public function removeDocumentation(\AppBundle\Entity\Documentation $documentation)
    {
        $this->documentations->removeElement($documentation);
    }

    /**
     * Get documentations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentations()
    {
        return $this->documentations;
    }
}