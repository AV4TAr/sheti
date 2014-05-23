<?php
return array(
    'modules' => array(
        'ShowMeTheIssue','BitbucketConnector',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
            './vendor',
        ),
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
//         'config_cache_enabled' => true,
//         'config_cache_key' => 'configuration',
//         'module_map_cache_enabled' => true,
//         'module_map_cache_key' => 'module-map',
//         'cache_dir' => './data/cache',
    ),
);
