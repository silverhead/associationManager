<?php
namespace AppBundle\Manager;

interface PaginatorFilterable
{
    public function addFilter(FilterQuery $filterQuery, string $key = "");

}