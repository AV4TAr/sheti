<?php
namespace BitbucketConnector;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 * @author diego
 *
 */
class BitbucketServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $service = new BitbucketService($config['bitbucket-connector']);

        return $service;
    }
}
