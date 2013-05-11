<?php

namespace Sknpp;

use Zend\Console\Console;
use Zend\Mvc\Router\RouteMatch;

class Module
{
    protected function _autoCssFactory()
    {
        return function ($sm) {
            // $sm instanceof Zend\View\HelperPluginManager
            $services = $sm->getServiceLocator();
            $helper = new View\Helper\AutoCss();
            $match = $services->get('application')
                ->getMvcEvent()
                ->getRouteMatch();

            if ($match instanceof RouteMatch) {
				$helper->setRouteMatch($match);
            }
            return $helper;
        };
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'autoCss' => $this->_autoCssFactory()
            ),
        );
    }
}
