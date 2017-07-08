<?php

namespace AppBundle\Service;

/**
 * Class SqlWhereParamsSetter
 * @package AppBundle\Service
 */
class SqlWhereParamsSetter
{
    /**
     * @var array
     */
    private $params = array();

    /**
     * @var string
     */
    private $allowParamsFormat;

    /**
     * Get query parameters (Array to String Format)
     * Here we get all params send in function for search
     * Work with key LIKE 'value%'
     *
     * @return $this
     */
    private function getAllowedParameters()
    {
        $params         = $this->params;
        $totalSearches  = count($params);
        $searches       = [];

        foreach ($params as $key => $value)
            $searches[] = $key . " LIKE " . "'" . $value . "%'";

        if (1 < $totalSearches)
            $searches = implode(' AND ', $searches);
        elseif (1 == $totalSearches)
            $searches = $searches[0];
        else
            $searches = '0=0';

        $this->allowParamsFormat = (string) $searches;

        return $this;
    }

    /**
     * Setter $params
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return string
     */
    public function getParams()
    {
        $this->getAllowedParameters();

        return $this->allowParamsFormat;
    }

}