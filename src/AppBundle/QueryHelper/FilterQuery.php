<?php

namespace AppBundle\QueryHelper;


class FilterQuery
{
    const OPERATOR_IN = 'in';

    /**
     * @var string
     */
    private $entityProperty;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string
     */
    private $value;

    public function __construct(string $entityProperty, $value, string $operator = "=")
    {
        $this->entityProperty = $entityProperty;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getEntityProperty(): string
    {
        return $this->entityProperty;
    }

    /**
     * @param string $entityProperty
     * @return FilterQuery
     */
    public function setEntityProperty(string $entityProperty): FilterQuery
    {
        $this->entityProperty = $entityProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return FilterQuery
     */
    public function setOperator(string $operator): FilterQuery
    {
        $this->operator = $operator;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return FilterQuery
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
}