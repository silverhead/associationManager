<?php
/**
 * Created by PhpStorm.
 * User: nicolaspin
 * Date: 08/05/2017
 * Time: 23:17
 */

namespace AppBundle\Handler;


use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class PageHistoricalHandler
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $callBackUrl;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var string
     */
    private $sessionName;

    public function __construct(SessionInterface $session, string $sessionName)
    {
        $this->session = $session;
        $this->sessionName = $sessionName;
    }

    public function setCallbackUrl($name, $callBackUrl, $urlParams = [], $anchor = null)
    {
        $this->session->set(
            $this->sessionName.'_'.$name,
            // todo improve that
            (object) [
                'callbackUrl' => $callBackUrl,
                'urlParams' => $urlParams,
                'anchor' => $anchor,
            ]
        );

        return $this;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getCallbackUrl(string $name)
    {
        if(null === $this->session->get($this->sessionName.'_'.$name)){
            return null;
        }

        return $this->formatUrl($this->session->get($this->sessionName.'_'.$name));
    }

    private function formatUrl($callbackUrl)
    {
        $url  =  $callbackUrl->callbackUrl;
        $params = [];
        foreach($callbackUrl->urlParams as $key => $value){
            $params[] = $key."=".$value;
        }
        $url .=  (!empty($callbackUrl->urlParams)?"?":"");
        $url .= implode("&", $params);
        if(null !== $callbackUrl->anchor){
            $url .= "#".$callbackUrl->anchor;
        }

        return $url;
    }
}