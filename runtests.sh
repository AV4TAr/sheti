#!/bin/sh

for FILE in module/*/tests/phpunit.xml;
do
    DIR=$(dirname ${FILE});
    cd $DIR
    ../../../vendor/bin/phpunit
    cd ../../../
done
