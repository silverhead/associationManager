<?php

namespace AppBundle\Manager;

interface PaginatorManagerInterface extends EntityManagerInterface
{
    /**
     * @param array $options
     * @return mixed
     */
    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page');
}