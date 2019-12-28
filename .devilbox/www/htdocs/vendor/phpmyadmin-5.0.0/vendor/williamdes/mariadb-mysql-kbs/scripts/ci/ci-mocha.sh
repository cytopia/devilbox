#!/bin/bash
cd $(dirname $0)/../../
echo "Running in : $(pwd)"
npm run test
npm run report-coverage
