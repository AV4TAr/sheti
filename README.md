# SHow mE the Issues

SHETI : pronounced /ˈʃeti/

The prupose of this app is to publish issues from different code repositories (bitbucket and github are currently supported) into hipchat rooms.

It's a ZF2 cli app that will connect to repos, get the issues, process them and publish them in hipchat rooms.

## Problem

   We use bitbucket issue management module to handle bugs / features.
   The "bug" email notification is lost in the avalanche of notifications we receive daily.
   We need a way of exposing bugs for the team involved in the project

# Installation

1. Clone project into your working directory
2. Use composer to get dependencies
   
   ```bash
   $ php composer.phar install
   ```
   
3. Copy /config/autoload/local.php.dist to /config/autoload/local.php.

    ```bash
    $ cp /config/autoload/local.php.dist /config/autoload/local.php
    ```

4. Customize /config/autoload/local.php as needed for your environment.
5. Create

# Configuration


# Usage

```
$ index.php issues process [--add-image] [--enable-hipchat] [--hipchat-room=] [--verbose|-v] [--repo=]
```

# TODO

  - Add tests for service & Connectors
  - Create a service for SHETI
  - Add connegtor for GitHub (doing)
  - Add connector for Jira
  - Move HipchatPublisher to a module
  - Add Publisher for IRC
  - Make issue processor event driven and make publisher listen those.
  - Create template / entity for publisher messages.
