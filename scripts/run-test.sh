#!/usr/bin/env bash

cd `dirname $0`

PHP_UNIT=$PWD/../vendor/bin/phpunit
TEST_DIR=$PWD/../application/gitzwart/test/



echo $TEST_DIR$1;

php $PHP_UNIT $TEST_DIR$1;