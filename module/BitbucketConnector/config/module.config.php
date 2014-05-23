<?php
use BitbucketConnector\BitbucketService;
return [
    'show-me-the-issue' => [
        'service-mapper' => [
            'bitbucket' => 'BitbucketService'
        ]
    ],
    'bitbucket-connector' => [
        'account-name' => 'caseinc',
        'oauth' => [
            'oauth_consumer_key' => '',
            'oauth_consumer_secret' => ''
        ],
        'issue-filters' => [
            'status' => 'new',
            'kind' => 'bug'
        ]
    ],
    
    'service_manager' => [
        'factories' => [
            'BitbucketConnector\BitbucketService' => function ($sm)
            {
                
                $config = $sm->get('config');
                return new BitbucketService($config['bitbucket-connector']);
            }
        ],
        'aliases' => [
           'BitbucketService' => 'BitbucketConnector\BitbucketService', 
        ]
    ]
];
