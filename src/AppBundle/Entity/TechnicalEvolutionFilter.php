<?php

namespace AppBundle\Entity;

/**
 * Class TechnicalEvolutionFilter
 * @package AppBundle\Entity
 */
class TechnicalEvolutionFilter
{
    /** @var string */
    public $title;

    /** @var Dictionary */
    public $status;

    /** @var Dictionary */
    public $categoryType;

    /** @var Category */
    public $category;

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     * @return TechnicalEvolutionFilter
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Dictionary
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return TechnicalEvolutionFilter
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Dictionary
     */
    public function getCategoryType()
    {
        return $this->categoryType;
    }

    /**
     * @param $categoryType
     * @return TechnicalEvolutionFilter
     */
    public function setCategoryType($categoryType)
    {
        $this->categoryType = $categoryType;
        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param $category
     * @return TechnicalEvolutionFilter
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Transform object to array
     * @return array
     */
    public function getArray()
    {
        return [
            'title'         => $this->getTitle(),
            'status'        => $this->getStatus(),
            'category_type'  => $this->getCategoryType(),
            'category'      => $this->getCategory()
        ];
    }
}