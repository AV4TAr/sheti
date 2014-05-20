<?php
return array(
    'show-me-the-issue' => array(
        'bitbucket' => [
            'account-name' => 'caseinc',
            'oauth' => [
                'oauth_consumer_key' => '',
                'oauth_consumer_secret' => ''
            ]
        ],
        'hipchat' => [
            'api-token' => ''
        ],
        'repo-mapping' => [
//             [
//                 'repo-type' => 'bitbucket',
//                 'repo' => 'lepatner-arazoo',
//                 'issue-tracker-link' => 'https://bitbucket.org/caseinc/lepatner-arazoo/issues?status=new&status=open',
//                 'hipchat-room' => '573590',
//                 'skip' => true
//             ],
           
        ]
    ),
    
    'controllers' => array(
        'invokables' => array(
            'ShowMeTheIssue\Controller\Show' => 'ShowMeTheIssue\Controller\ShowController'
        )
    ),
    
    'console' => array(
        'router' => array(
            'routes' => array(
                'get-issues' => array(
                    'options' => array(
                        'route' => 'issues process [--add-image] [--enable-hipchat] [--hipchat-room=] [--repo=] [--verbose|-v]',
                        'defaults' => array(
                            'controller' => 'ShowMeTheIssue\Controller\Show',
                            'action' => 'process'
                        )
                    )
                )
            )
        )
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        )
    )
);
