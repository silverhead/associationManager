<?php

namespace AppBundle\Entity;

class DashboardBundle
{
    /**
     * @var string
     */
    private $bundleCode;

    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $action;

    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $jsFiles;

    public function __construct()
    {
        $this->jsFiles = array();
    }

    /**
     * @return string
     */
    public function getBundleCode():? string
    {
        return $this->bundleCode;
    }

    /**
     * @param string $bundleCode
     * @return DashboardBundle
     */
    public function setBundleCode(string $bundleCode): DashboardBundle
    {
        $this->bundleCode = $bundleCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getService():? string
    {
        return $this->service;
    }

    /**
     * @param string $service
     * @return DashboardBundle
     */
    public function setService(string $service): DashboardBundle
    {
        $this->service = $service;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction():? string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return DashboardBundle
     */
    public function setAction(string $action): DashboardBundle
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel():? string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return DashboardBundle
     */
    public function setLabel(string $label): DashboardBundle
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return array
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    /**
     * @param array $jsFiles
     * @return DashboardBundle
     */
    public function setJsFiles(array $jsFiles): DashboardBundle
    {
        $this->jsFiles = $jsFiles;

        return $this;
    }


}
