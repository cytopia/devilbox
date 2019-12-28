#!/bin/bash
cd $(dirname $0)/../../
echo "Running in : $(pwd)"
echo "Running jshint"
npm run jshint -- --verbose
JSHINT=$?
echo "Running prettier"
npm run prettier -- --list-different
PRETTIER=$?

if [[ $JSHINT != 0 ]] || [[ $PRETTIER != 0 ]]; then
    echo "You have some errors to fix !";
    exit 1;
fi
