<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 10/05/2017
 * Time: 23:51
 */

namespace AppBundle\Manager;


use AppBundle\Repository\PaginatorRepositoryInterface;
use Knp\Component\Pager\PaginatorInterface;

Trait PaginatorManagerTrait
{
    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page')
    {
        if( !($this instanceof PaginatorManagerInterface)){
            throw new \Exception("You need to implement PaginatorManagerInterface for use this trait!");
        }

        if( !($this->getRepository() instanceof PaginatorRepositoryInterface)){
            throw new \Exception("The repository must implements PaginatorRepository");
        }

        if( !isset($this->paginator) || !($this->paginator instanceof PaginatorInterface)){
            throw new \Exception("You need to create a property named \"paginator\" which implements PaginatorInterface!");
        }

        $qb = $this->getRepository()->getQbPaginatedList();

        return $this->paginator->paginate($qb, $page, $itemPerPage, [
            'pageParameterName' => $pageParameterName,
            'anchor' => '#periodicities'
        ]);
    }
}