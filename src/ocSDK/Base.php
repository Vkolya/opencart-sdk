<?php
namespace Vkolya\ocSDK;
use Vkolya\ocSDK\OpenCart;
class Base {
    public $dynamicRoute = array();
    protected $oc;
    protected $curl;
    
    public function __construct(OpenCart $oc) {
        $this->oc = $oc;
        $this->curl = $oc->curl;
        $classParts = explode('\\', get_class($this));
        $class = end($classParts);
        if ($class != 'Base') {
            $this->dynamicRoute[] = strtolower($class);
        }
    }
    public function __get($name) {
        $voidProp = new Base($this->oc);
        $voidProp->dynamicRoute = $this->dynamicRoute;
        $voidProp->dynamicRoute[] = $name;
        return $voidProp;
    }
    public function __call($name, $args) {
	$postData = $args[0];
        $dynamicRoute = $this->dynamicRoute;
        $dynamicRoute[] = $name;
        $route = implode('/', $dynamicRoute);
        $this->curl->setUrl($this->oc->getUrl($route));
        $this->curl->setData($postData);
        $this->curl->makeRequest();
        return $this->curl->getResponse();
    }
}