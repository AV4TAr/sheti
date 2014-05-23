<?php
use GithubConnector\GithubService;
return array(
    'show-me-the-issue' => [
        'service-mapper' => [
            'github' => 'GithubService'
        ]
    ],
    'console' => array(
        'router' => array(
            'routes' => array(
                'test-github' => array(
                    'options' => array(
                        'route' => 'test',
                        'defaults' => array(
                            'controller' => 'GithubConnector\Controller\Github',
                            'action' => 'index'
                        )
                    )
                )
            )
        )
    ),
    
    'controllers' => array(
        'invokables' => array(
            'GithubConnector\Controller\Github' => 'GithubConnector\Controller\GithubController'
        )
    ),
    
    'service_manager' => [
        'factories' => [
            'GithubConnector\GithubService' => function ($sm)
            {
                $config = $sm->get('config');
                return new GithubService($config['github-connector']);
            }
        ],
        'aliases' => [
            'GithubService' => 'GithubConnector\GithubService'
        ]
    ]
);
