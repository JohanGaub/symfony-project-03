<?php

namespace AppBundle\Repository;

use Doctrine\ORM\Query\ResultSetMapping;

/**
 * Class EntityFieldResult
 * @package AppBundle\Repository
 */
class EntityFieldResult
{
    /**
     * @var ResultSetMapping
     */
    private $rsm;

    /**
     * EntityFieldResult constructor.
     * @param ResultSetMapping $rsm
     */
    public function __construct(ResultSetMapping $rsm)
    {
        $this->rsm = $rsm;
    }

    /**
     * Set Basic fields for technicalEvolution
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmBasicTechnicalEvolution(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'id', 'id');
        $this->rsm->addFieldResult($alias, 'title', 'title');
        $this->rsm->addFieldResult($alias, 'sum_up', 'sumUp');
        $this->rsm->addFieldResult($alias, 'expected_delay', 'expectedDelay');
        $this->rsm->addFieldResult($alias, 'creation_date', 'creationDate');
        $this->rsm->addFieldResult($alias, 'update_date', 'updateDate');

        return $this->rsm;
    }

    /**
     * Set dictionary status fields
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmDictionaryStatus(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'dictionary_tes_id', 'id');
        $this->rsm->addFieldResult($alias, 'dictionary_tes_type', 'type');
        $this->rsm->addFieldResult($alias, 'dictionary_tes_value', 'value');
        
        return $this->rsm;
    }

    /**
     * Set dictionary origin fields
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmDictionaryOrigin(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'dictionary_teo_id', 'id');
        $this->rsm->addFieldResult($alias, 'dictionary_teo_type', 'type');
        $this->rsm->addFieldResult($alias, 'dictionary_teo_value', 'value');
        
        return $this->rsm;
    }

    /**
     * Set basic category fields
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmCategory(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'category_id', 'id');
        $this->rsm->addFieldResult($alias, 'category_title', 'title');

        return $this->rsm;
    }

    /**
     * Set basic category type fields
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmCategoryType(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'dictionary_ct_id', 'id');
        $this->rsm->addFieldResult($alias, 'dictionary_ct_type', 'type');
        $this->rsm->addFieldResult($alias, 'dictionary_ct_value', 'value');
        
        return $this->rsm;
    }

    /**
     * Set basic user technical evolution fields
     *
     * @param string $alias
     * @return ResultSetMapping
     */
    public function rsmUserTechnicalEvolution(string $alias)
    {
        $this->rsm->addFieldResult($alias, 'ute_id', 'id');
        $this->rsm->addFieldResult($alias, 'ute_note', 'note');
        $this->rsm->addFieldResult($alias, 'ute_comment', 'comment');
        $this->rsm->addFieldResult($alias, 'ute_technical_evolution_id', 'technicalEvolution');

        return $this->rsm;
    }

}