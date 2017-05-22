<?php

namespace AppBundle\Entity;

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
     * @ORM\OneToMany(targetEntity="Documentation", mappedBy="product")
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
        $this->faqs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->technicalEvolutions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add faq
     *
     * @param \AppBundle\Entity\Faq $faq
     *
     * @return Product
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
     * Add technicalEvolution
     *
     * @param \AppBundle\Entity\TechnicalEvolution $technicalEvolution
     *
     * @return Product
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
     * Add documentation
     *
     * @param \AppBundle\Entity\Documentation $documentation
     *
     * @return Product
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
