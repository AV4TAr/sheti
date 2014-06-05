#!/bin/sh

./vendor/bin/php-cs-fixer -v fix ./module --dry-run --config-file ./.php_cs

for FILE in module/*/test/phpunit.xml;
do
    DIR=$(dirname ${FILE});
    cd $DIR
    ../../../vendor/bin/phpunit
    cd ../../../
done
