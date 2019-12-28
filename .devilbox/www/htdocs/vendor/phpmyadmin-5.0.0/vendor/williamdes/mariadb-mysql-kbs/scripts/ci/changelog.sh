#!/bin/bash
npm install --only=dev
npm run changelog-file
git diff --no-prefix CHANGELOG.md
