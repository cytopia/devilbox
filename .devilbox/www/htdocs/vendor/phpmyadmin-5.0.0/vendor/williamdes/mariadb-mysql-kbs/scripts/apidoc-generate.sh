#!/bin/bash
cd $(dirname $0)/../scripts
echo "Running in : $(pwd)"

composer require --no-interaction sami/sami --prefer-dist
./vendor/bin/sami.php --no-interaction --verbose update ./sami-config.php
