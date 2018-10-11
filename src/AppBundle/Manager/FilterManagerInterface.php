<?php

namespace AppBundle\Manager;


use AppBundle\QueryHelper\FilterQuery;

interface FilterManagerInterface
{
    /**
     * @param string $dqlProperty
     * @param $value any type of value
     * @param string $operator
     * @return mixed
     */
    public function addFilter(FilterQuery $query, $key);
}