<?php

namespace Sknpp\View\Helper;

use Zend\Mvc\Router\RouteMatch;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\HeadLink;

class AutoCss extends AbstractHelper
{
    protected $_routeMatch;
    
    protected $_params = array();
    
    protected $_cssPath;
    
    protected $_cssLocation;
    
    public function setRouteMatch(RouteMatch $match)
    {
        $this->_routeMatch = $match;
        return $this;
    }
    
    public function setCssPath($path)
    {
        $this->_cssPath = $path;
        return $this;
    }
    
    public function getCssPath()
    {
        if(null === $this->_cssPath) {
            $this->setCssPath(join(DIRECTORY_SEPARATOR, array(
                getcwd(), 'public', 'css')));    
        }
        return $this->_cssPath;
    }
    
    public function setCssLocation($location)
    {
        $this->_cssLocation = $location;
        return $this;
    }
    
    public function getCssLocation()
    {
        if(null === $this->_cssLocation) {
            $this->setCssLocation('/css');
        }
        return $this->_cssLocation;
    }
    
    public function __invoke(args $args = null)
    {
        if(!$this->_routeMatch) {
            return;
        }
        
        $params = $this->_routeMatch->getParams();
        if(isset($params['controller'])) {
            $controller = $params['controller'];
            $parts = explode('\\',$controller);
            $this->_params['module'] = strtolower(reset($parts));
            $this->_params['controller'] = strtolower(end($parts));
        }
        if(isset($params['action'])) {
            $this->_params['action'] = strtolower($params['action']);
        }
        return $this;
    }
    
	public function __toString()
	{
		return $this->toString();
	}
    
    public function toString($indent = null)
    {
        $helper = new HeadLink();
        $this->_moduleCss($helper);
        $this->_controllerCss($helper);
        $this->_actionCss($helper);
	    return $helper->toString();
	}
    
    protected function _moduleCss($helper)
    {
        $file = join(DIRECTORY_SEPARATOR,array(
            $this->getCssPath(), $this->_params['module'] . '.css'));
        $href = join('/', array(
            $this->getCssLocation(), $this->_params['module'] . '.css'));
        if(file_exists($file)) {
            $helper->append($helper->createDataStylesheet(array($href)));
        }
	}
    
    protected function _controllerCss($helper)
    {
        $file = join(DIRECTORY_SEPARATOR, array(
            $this->getCssPath(), $this->_params['module'],
            $this->_params['controller'] . '.css'));
        $href = join('/', array(
            $this->getCssLocation(), $this->_params['module'],
            $this->_params['controller'] . '.css'));
        if(file_exists($file)) {
            $helper->append($helper->createDataStylesheet(array($href)));
        }
    }
    
    protected function _actionCss($helper)
    {
        $file = join(DIRECTORY_SEPARATOR, array(
            $this->getCssPath(), $this->_params['module'],
            $this->_params['controller'],
            $this->_params['action'] . '.css'));
        $href = join('/', array(
            $this->getCssLocation(), $this->_params['module'],
            $this->_params['controller'],
            $this->_params['action'] . '.css'));
        if(file_exists($file)) {
            $helper->append($helper->createDataStylesheet(array($href)));
        }
    }
}
