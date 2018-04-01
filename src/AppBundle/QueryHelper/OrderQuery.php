<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 31/03/2018
 * Time: 18:26
 */

namespace AppBundle\QueryHelper;


class OrderQuery
{
    public const DESC = "DESC";
    public const ASC = "ASC";

    /**
     * @var string
     */
    private $sort;

    /**
     * @var string
     */
    private $order;

    public function __construct(string $sort, $order = self::ASC)
    {
        $this->sort = $sort;
        $this->order = $order;
    }

    /**
     * @return string
     */
    public function getSort(): string
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     * @return OrderQuery
     */
    public function setSort(string $sort): OrderQuery
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     * @return OrderQuery
     */
    public function setOrder(string $order): OrderQuery
    {
        $this->order = $order;

        return $this;
    }
}