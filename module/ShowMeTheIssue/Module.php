<?php

namespace ShowMeTheIssue;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface as Console;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return array_merge(
            include __DIR__ . '/config/module.config.php',
            include __DIR__ . '/config/images.config.php'
        );
    }

    public function getConsoleBanner(Console $console){
        return "Show Me The Issue v0.1";
    }
    
    public function getConsoleUsage(Console $console){
        return array(
            'Posts issues to hipchat room',
            'issues process [--add-image] [--enable-hipchat] [--hipchat-room=] [--verbose|-v]'    => 'Process issues.',
            array('--add-image', 'Add image when publishing'),
            array('--enable-hipchat', 'Publish issues to hipchat'),
            array('--hipchat-room=HIPCHAT_GROUP_ID', 'Use a default room to post issues, used for debugging'),
            array('--verbose|-v', '(optional) turn on verbose mode')
        );
    }
    
    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
}
