<?php

namespace AppBundle\Manager;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;

Trait PaginatorManagerTrait
{

    public function paginatedFilteredAndOrdered($filters = array(), $orders = array(), $page = 1, $itemPerPage = 10, $pageParameterName = 'page', $anchor = null, $route = null)
    {
        $this->checkUsingInterface();

        /**
         * @var QueryBuilder
         */
        $qb = $this->getRepository()->getQbPaginatedList();

        foreach($filters as $i => $data){
            $property   = $data['property'];
            $search     = $data['search'];
            $operation  = $data['operation'];
            $pattern    = "value".$i;

            $qb->andWhere($property ." ".$operation." :".$pattern)->setParameter($pattern, $search);
        }

        foreach($orders as $data){
            $qb->addOrderBy($data[0], $data[1]);
        }


        $paginate = $this->paginator->paginate($qb, $page, $itemPerPage, [
            'pageParameterName' => $pageParameterName,
            'anchor' => $anchor
        ]);

        if(null !== $route){
            $paginate->setUsedRoute($route);
        }

        return $paginate;
    }

    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page', $anchor = null, $route = null)
    {
        $this->checkUsingInterface();

        $qb = $this->getRepository()->getQbPaginatedList();

        $paginate = $this->paginator->paginate($qb, $page, $itemPerPage, [
            'pageParameterName' => $pageParameterName,
            'anchor' => $anchor
        ]);

        if(null !== $route){
            $paginate->setUsedRoute($route);
        }

        return $paginate;
    }

    private function checkUsingInterface()
    {
        if( !($this instanceof PaginatorManagerInterface)){
            throw new \Exception("You need to implement PaginatorManagerInterface for use this trait!");
        }

        if( !($this->getRepository() instanceof PaginatorRepositoryInterface)){
            throw new \Exception("The repository must implements PaginatorRepositoryInterface!");
        }

        if( !isset($this->paginator)){
            throw new \Exception("You need to create a property named \"paginator\" which implements PaginatorInterface!");
        }

        if(!($this->paginator instanceof PaginatorInterface)){
            throw new \Exception("The paginator property must to implement PaginatorInterface!");
        }
    }
}