<?php
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
            'BitbucketConnector\BitbucketService' => 'BitbucketConnector\BitbucketServiceFactory'
        ],
        'aliases' => [
           'BitbucketService' => 'BitbucketConnector\BitbucketService',
        ]
    ]
];
