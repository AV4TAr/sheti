<?php
return array(
    'show-me-the-issue' => [
        'hipchat' => [
            'api-token' => ''
        ],
        'repo-mapping' =>         // []
        [
            'repo-type' => 'bitbucket',
            'repo' => 'lepatner-arazoo',
            'issue-tracker-link' => 'https://bitbucket.org/caseinc/lepatner-arazoo/issues?status=new&status=open',
            'hipchat-room' => '573590',
            'skip' => true
        ]
    ],
    
    'controllers' => [
        'invokables' => [],
        'factories' => [
            'ShowMeTheIssue\Controller\Show' => 'ShowMeTheIssue\Controller\ShowControllerFactory'
        ]
    ],
    
    'console' => [
        'router' => [
            'routes' => [
                'get-issues' => [
                    'options' => [
                        'route' => 'issues process [--add-image] [--enable-hipchat] [--hipchat-room=] [--repo=] [--verbose|-v]',
                        'defaults' => [
                            'controller' => 'ShowMeTheIssue\Controller\Show',
                            'action' => 'process'
                        ]
                    ]
                ]
            ]
        ]
    ],
    'service_manager' => [
        'factories' => [
            'IssueCacheListener' => function($sm){
                $cache = $sm->get('Cache\Issues');
                $log   = $sm->get('Log\Issues');
                return new \ShowMeTheIssue\Listener\IssueCacheListener($cache, $log);
            }
        ],
        'invokables' => [
            'ShowMeTheIssue\Issue' => 'ShowMeTheIssue\Entity\Issue',
            //'ShowMeTheIssue\Listener\IssueCacheListener' => 'ShowMeTheIssue\Listener\IssueCacheListener'
        ],
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ],
        'aliases' => [
            'Issue' => 'ShowMeTheIssue\Issue'
        ],
        'shared' => [
            'ShowMeTheIssue\Issue' => FALSE
        ]
    ],
    'caches' => [
        'Cache\Issues' => [
            'adapter' => 'filesystem',
            'options' => [
                'ttl' => 1800,
                'cache_dir' => 'data/cache',
            ],
            'plugins' => array(
                'exception_handler' => array(
                    'throw_exceptions' => false
                ),
                'serializer',
            )
        ],
     ],
    'log' => [
        'Log\Issues' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'priority' => 1000,
                    'options' => [
                        'stream' => 'data/logs/issues.log',
                    ],
                ],
            ],
        ],
    ],
)
;
