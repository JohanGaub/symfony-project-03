<?php

namespace AppBundle\Entity;

/**
 * Class TechnicalEvolutionFilter
 * @package AppBundle\Entity
 */
class TechnicalEvolutionFilter
{
    /** @var string */
    private $title;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return TechnicalEvolutionFilter
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }
}