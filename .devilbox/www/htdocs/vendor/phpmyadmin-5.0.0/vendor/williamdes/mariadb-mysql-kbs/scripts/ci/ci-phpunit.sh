#!/bin/bash
cd $(dirname $0)/../../
echo "Running in : $(pwd)"
composer run test
