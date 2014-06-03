<?php
namespace ShowMeTheIssue\Listener;

use Zend\ServiceManager\FactoryInterface;

/**
 *
 * @author diego
 *        
 */
class IssueCacheListenerFactory implements FactoryInterface
{
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $cache = $serviceLocator->get('Cache\Issues');
        $log   = $serviceLocator->get('Log\Issues');
        return new \ShowMeTheIssue\Listener\IssueCacheListener($cache, $log);
    }
}
