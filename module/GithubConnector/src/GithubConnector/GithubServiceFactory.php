<?php
namespace GithubConnector;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 *
 * @author diego
 *        
 */
class GithubServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator){
        $config = $serviceLocator->get('config');
        $service = new GithubService($config['github-connector'], $serviceLocator);
        return $service;
    }
}
