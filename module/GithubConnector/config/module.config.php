<?php
use GithubConnector\GithubService;
return array(
    'show-me-the-issue' => [
        'service-mapper' => [
            'github' => 'GithubService'
        ]
    ],
    'github-connector' => [
        /**
         * Account name user or team
         */
        'account-name' => '',
        /**
         * Default filters for issues
         */
        'issue-filters' => [ // How to filter issues
            'state' => 'open'
        ],
        /**
         * User to use for connection
         */
        'user_connect' => [
            'username' => '',
            'password' => ''
        ],
        /**
         * Application OAuth if no user provided
         */
        'oauth' => [
            'oauth_consumer_key' => '',
            'oauth_consumer_secret' => ''
        ]
    ],
    'service_manager' => [
        'factories' => [
            'GithubConnector\GithubService' => function ($sm)
            {
                $config = $sm->get('config');
                $githubService = new GithubService($config['github-connector']);
                $githubService->setServiceLocator($sm);
                return $githubService;
            }
        ],
        'aliases' => [
            'GithubService' => 'GithubConnector\GithubService'
        ]
    ]
);
