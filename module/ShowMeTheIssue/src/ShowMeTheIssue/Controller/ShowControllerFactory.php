<?php
namespace ShowMeTheIssue\Controller;

use Zend\ServiceManager\FactoryInterface;

/**
 *
 * @author diego
 *
 */
class ShowControllerFactory implements FactoryInterface
{
    /**
     * @param Zend\Mvc\Controller\ControllerManager $serviceLocator
     */
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $log = $serviceLocator->getServiceLocator()->get('Log\Issues');

        return new ShowController($log);
    }
}
