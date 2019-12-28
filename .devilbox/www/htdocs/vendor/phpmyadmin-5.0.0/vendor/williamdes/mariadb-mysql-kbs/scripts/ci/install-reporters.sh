#!/usr/bin/env bash
therealpath ()
{
    f=$@;
    if [ -d "$f" ]; then
        base="";
        dir="$f";
    else
        base="/$(basename "$f")";
        dir=$(dirname "$f");
    fi;
    dir=$(cd "$dir" && /bin/pwd);
    echo "$dir$base"
}
ME="$(therealpath $(dirname $0))"
echo "I am in : $ME"
mkdir -p "$HOME/.cache/ci"
cd "$HOME/.cache/ci"
CODACY_LATEST_PHAR=$(curl -s https://api.github.com/repos/codacy/php-codacy-coverage/releases/latest | grep browser_download_url | cut -d '"' -f 4)
echo "CODACY_LATEST_PHAR: $CODACY_LATEST_PHAR"
wget --timestamping "$CODACY_LATEST_PHAR"

cp "$HOME/.cache/ci/codacy-coverage.phar" "$ME/../../codacy-coverage.phar"

chmod +x "$ME/../../codacy-coverage.phar"

cd "$ME/../../"
