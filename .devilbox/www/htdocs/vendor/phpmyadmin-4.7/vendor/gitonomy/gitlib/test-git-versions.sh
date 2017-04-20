#!/bin/bash
set -e

URL="git@github.com:git/git.git"
VERSIONS="v1.6.3 v1.6.6 v1.7.1 v1.7.5 v1.7.9 v1.8.1"
DIRNAME="git-builds"

if [ ! -d "$DIRNAME" ]; then
    mkdir - "$DIRNAME"
fi

cd "$DIRNAME"

# remote repository
if [ -d cache ]; then
    echo "- update from github..."
    cd cache
    git fetch -q
    cd ..
else
    echo "- clone from github..."
    git clone "$URL" cache -q
fi

# build
for VERSION in $VERSIONS; do

    echo "- build $VERSION"
    LOGFILE="build.$VERSION.log"
    LOCKFILE="build.$VERSION.lock"

    if [ -f "$LOCKFILE" ]; then
        echo "    - lock present, destroy build..."
        rm -rf "$VERSION" "build.$VERSION.log"
    fi

    if [ -d "$VERSION" ]; then
        echo "    - already built, skipping..."
        continue
    fi

    touch "$LOCKFILE"

    echo "    - log: $LOGFILE"
    touch "$LOGFILE"
    mkdir "$VERSION"
    echo "    - clone repository"
    git clone -s cache "$VERSION/source" -q
    echo "    - checkout version"
    cd "$VERSION/source"
    git checkout "$VERSION" -q

    mkdir ../build
    PREFIX="`readlink -f ../build`"

    echo "    - build..."
    autoconf >> "../../$LOGFILE" 2>&1
    ./configure --prefix="$PREFIX" >> "../../$LOGFILE" 2>&1
    make all >> "../../$LOGFILE" 2>&1
    make install >> "../../$LOGFILE" 2>&1
    echo "    - build ok"
    cd ../..
    rm "$LOCKFILE"
done
cd ..

# test
echo ""
for VERSION in $VERSIONS; do
    echo "- test $VERSION"
    GIT_COMMAND="`readlink -f $DIRNAME/$VERSION/build/bin/git`"
    echo "  - command: $GIT_COMMAND"
    GIT_COMMAND="$GIT_COMMAND" phpunit || true
done
