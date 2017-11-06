<?php

namespace AppBundle\Manager;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

Trait PaginatorOrderedManagerTrait
{
    use PaginatorManagerTrait;
    
    /**
     * 
     * @var SessionInterface
     */
    protected $session;
    
    /**
     *
     * @var string
     */
    protected $namespace;
    
    protected $paginatorOrders;
    
    /**
     * 
     * @var array
     */
    protected $orderedTableCorresondance;
    
    protected function setPaginatorOrederedNamespace(string $namespace)
    {
        $this->namespace = $namespace;        
    }
    
    protected function setOrderedTableCorrespondence(array $orderedTableCorrespondance)
    {
        $this->orderedTableCorresondance = $orderedTableCorrespondance;
    }
    
    public function getPaginatorOrders()
    {
        return $this->paginatorOrders;
    }
    
    public function setPaginatorOrders(array $orders)
    {
        $this->paginatorOrders = $orders;
        $this->session->set($this->namespace, $this->paginatorOrders);
        
        return $this;
    }
    
    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page', $anchor = null, $route = null)
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

        /**
         * 
         * @var QueryBuilder $qb
         */
        $qb = $this->getRepository()->getQbPaginatedList();
        
        if(null === $this->paginatorOrders){
            throw new \Exception("Please set the orders pagination with method setPaginatorOrders!");
        }
        
        foreach($this->paginatorOrders as $key => $order){
            $sort = $this->orderedTableCorresondance[$key];
            if($order != ''){
                $qb->addOrderBy($sort, $order);
            }
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
}