<?php

namespace AppBundle\Manager;

use AppBundle\Repository\PaginatorRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use AppBundle\QueryHelper\FilterQuery;
use AppBundle\QueryHelper\OrderQuery;

Trait PaginatorManagerTrait
{
    /**
     *
     * @var string
     */
    protected $cacheNamespace;

    /**
     * @var bool
     */
    protected $activeCache = false;

    /**
     * @var ArrayCollection
     */
    protected $filters;

    /**
     * @var ArrayCollection
     */
    protected $orders;

    public function activateCache(string $cacheNamespace)
    {
        $this->namespace = $cacheNamespace;
        $this->activeCache = true;
    }

    public function addFilter(FilterQuery $filterQuery, string $key = "")
    {
        if($this->filters == null){
            $this->filters = new ArrayCollection();
        }

        if($key == ""){
            $key = $filterQuery->getEntityProperty();
        }

        $this->filters->set($key, $filterQuery);

        return $this;
    }

    public function addOrder(OrderQuery $orderQuery, string $key = "")
    {
        if($this->orders == null){
            $this->orders = new ArrayCollection();
        }

        if($key == ""){
            $key = $orderQuery->getSort();
        }

        $this->orders->set($key, $orderQuery);

        return $this;
    }

    /**
     * @param array $filters
     * @param array $orders
     * @return QueryBuilder
     */
    private function filteringList(QueryBuilder $qb): QueryBuilder
    {
        $authorizedOperators = array(
            '=', '>', '<', '>=', '<=',
            '%like%', 'like%', '%like', '%notlike%', 'notlike%', '%notlike',
            'in', 'notin'
        );

        $filters = $this->filters;

        // If filters is not used them not use the filter system
        if($filters == null || $filters->isEmpty()){
            return $qb;
        }

        foreach($filters as $key => $filter){
            if(!in_array($filter->getOperator(),  $authorizedOperators)){
                throw new \Exception("the filter operator \”".$filter->getOperator()."\” is not recognized! Please
                check the \”".$authorizedOperators."\" for use the correctly operator !");
            }

            $property   = $filter->getEntityProperty();
            $operator  = $filter->getOperator();
            $search     = $filter->getValue();
            $pattern    = $key;

            if(!in_array($operator, array('in', 'notin', '%like', 'like%', '%like%', '%notlike', 'notlike%', '%notlike%'))){
                $qb->andWhere($property ." ".$operator." :".$pattern)->setParameter($pattern, $search);
            }
            else if(in_array($operator, array('%like', 'like%', '%like%', '%notlike', 'notlike%', '%notlike%'))){
                switch ($operator){
                    case 'like%':
                        $qb->andWhere($property ." LIKE :".$pattern)->setParameter($pattern, $search."%");
                        break;
                    case '%like':
                        $qb->andWhere($property ." LIKE :".$pattern)->setParameter($pattern, "%".$search);
                        break;
                    case '%like%':
                        $qb->andWhere($property ." LIKE :".$pattern)->setParameter($pattern, "%".$search."%");
                        break;
                    case 'notlike%':
                        $qb->andWhere($property ." NOT LIKE :".$pattern)->setParameter($pattern, $search."%");
                        break;
                    case '%notlike':
                        $qb->andWhere($property ." NOT LIKE :".$pattern)->setParameter($pattern, "%".$search);
                        break;
                    case '%notlike%':
                        $qb->andWhere($property ." NOT LIKE :".$pattern)->setParameter($pattern, "%".$search."%");
                        break;
                }
            }
            else if(in_array($operator, array('%in', 'notin'))){
                switch ($operator){
                    case 'in':
                        $qb->andWhere($property ." IN (:".$pattern.")")->setParameter($pattern, implode("','", $search));
                        break;
                    case 'not':
                        $qb->andWhere($property ." NOT IN (:".$pattern.")")->setParameter($pattern, implode("','", $search));
                        break;
                }
            }
        }

        if($this->activeCache){
            $this->putFiltersInCache();
        }

        return $qb;
    }

    private function orderingList(QueryBuilder $qb): QueryBuilder
    {
        $orders = $this->orders;

        // If $orders is not used them not use the order system
        if($orders == null || $orders->isEmpty()){
            return $qb;
        }

        foreach($orders as $order){
            $sort = $order->getSort();

            if($order->getOrder() != ''){
                $qb->addOrderBy($sort, $order->getOrder());
            }
        };

        if($this->activeCache){
            $this->putOrdersInCache();
        }

        return $qb;
    }

    private function getQueryList(): QueryBuilder
    {
        $this->checkUsingInterface();

        $qb = $this->getRepository()->getQbPaginatedList();

        $qb = $this->filteringList($qb);
        $qb = $this->orderingList($qb);

        return $qb;
    }

    public function getList($offset = 1, $limit = 10)
    {
        $qb = $this->getQueryList();

        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    public function paginatedList($page = 1, $itemPerPage = 10, $pageParameterName = 'page', $anchor = null, $route = null)
    {
        $qb = $this->getQueryList();

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

    private function iniSessionCache(){
        if(!$this->activeCache){
            throw new \Exception("Please active the cache list before it!");
        }

        if($this->session->get($this->namespace) === null){
            $this->session->set($this->namespace, array('filters' => array(), 'orders' => array()));
        }
    }

    private function putOrdersInCache(){
        $this->iniSessionCache();

        $cache = $this->session->get($this->namespace);
        $cache['orders'] = $this->orders;

        $this->session->set($this->namespace, $cache);
    }

    /**
     * @return ArrayCollection|null
     * @throws \Exception
     */
    public function getOrdersInCache(){
        $this->iniSessionCache();
        $cache = $this->session->get($this->namespace);
        return $cache['orders'];
    }

    public function getArrayOrdersInCacheByKey(array $defaultValue = array())
    {
        $orders = $defaultValue;
        foreach($this->getOrdersInCache() as $key => $order){
            $orders[$key] = $order->getOrder();
        }

        return $orders;
    }

    private function putFiltersInCache(){
        $this->iniSessionCache();

        $cache = $this->session->get($this->namespace);
        $cache['filters'] = $this->filters;

        $this->session->set($this->namespace, $cache);
    }

    /**
     * @return ArrayCollection|null
     * @throws \Exception
     */
    public function getFiltersInCache(){
        $this->iniSessionCache();
        $cache = $this->session->get($this->namespace);
        return $cache['filters'];
    }

    public function getArrayFiltersInCacheByKey(array $defaultValue)
    {
        $filters = array();
        foreach($this->getFiltersInCache() as $key => $filter){
            $filters[$key] = $filter->getValue();
        }

        return $filters;
    }

}