<?php

namespace AppBundle\Manager;

interface PaginatorManagerInterface extends EntityManagerInterface
{
    /**
     * 
     * @param number $page
     * @param number $itemPerPage
     * @param string $pageParameterName
     */
    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page');
}