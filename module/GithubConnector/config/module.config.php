<?php
use GithubConnector\GithubService;
return array(
    'show-me-the-issue' => [
        'service-mapper' => [
            'github' => 'GithubService'
        ]
    ],  
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
