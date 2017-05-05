<?php

namespace AppBundle\Manager;

interface PaginatorManagerInterface extends ListManagerInterface
{
    /**
     * @param array $options
     * @return mixed
     */
    public function paginatedList(array $options = []);
}