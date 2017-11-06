<?php

namespace AppBundle\Manager;

interface PaginatorOrderedManagerInterface extends PaginatorManagerInterface
{
    
//     protected function setPaginatorOrederedNamespace(string $namespace);
    
//     protected function setOrderedTableCorrespondence(array $orderedTableCorrespondance);
    
    function getPaginatorOrders();
    
    function setPaginatorOrders(array $orders);
}