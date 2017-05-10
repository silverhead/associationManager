<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 11/05/2017
 * Time: 00:01
 */

namespace AppBundle\Repository;


interface PaginatorRepositoryInterface
{
    public function getQbPaginatedList();
}