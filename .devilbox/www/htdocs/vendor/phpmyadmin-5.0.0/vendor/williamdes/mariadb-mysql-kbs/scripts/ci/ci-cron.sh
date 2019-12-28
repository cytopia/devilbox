#!/bin/bash
if [ "$TRAVIS_EVENT_TYPE" != "cron" ]; then
	echo "Not a travis cron !";
	exit 1;
fi

if [ "$TRAVIS_PULL_REQUEST" != "false" ]; then
	echo "Not a travis cron !";
	exit 1;
fi

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


ME="$(dirname $0)"

BOT_DIR_FILES="$(therealpath $ME/../sudo-bot)"
REPO_DIR="$(therealpath $ME/../../)"

echo "BOT_DIR_FILES = $BOT_DIR_FILES"
echo "REPO_DIR = $REPO_DIR"

REPO="mariadb-mysql-kbs"
OWNER="williamdes"


#INSTALLATION_ID="123456"
JWT_PRIV_KEY_PATH="$BOT_DIR_FILES/sudo.2018-09-12.private-key.pem"
GPG_PRIV_PATH="$BOT_DIR_FILES/privkey.asc"
GPG_PUB_PATH="$BOT_DIR_FILES/pubkey.asc"
#GPG_PRIV_PASSWORD="gpgPasswordHere"

BOT_NAME="Sudo Bot"
BOT_EMAIL="sudo-bot@wdes.fr"

echo "Create env file"

echo -e "JWT_PRIV_KEY_PATH=$JWT_PRIV_KEY_PATH\nGPG_PRIV_PATH=$GPG_PRIV_PATH\nGPG_PUB_PATH=$GPG_PUB_PATH\nGPG_PRIV_PASSWORD=$GPG_PRIV_PASSWORD\nREPO=$REPO\nOWNER=$OWNER\nASSIGN_USERS=williamdes\nAPP_ID=17453\nINSTALLATION_ID=$INSTALLATION_ID\nBOT_NAME=$BOT_NAME\nBOT_EMAIL=$BOT_EMAIL\nREPO_DIR=$REPO_DIR\nDOT_IGNORE=$BOT_DIR_FILES/.sudobotignore\nTEMPLATE_FILE=$BOT_DIR_FILES/template.js" > $BOT_DIR_FILES/.env


cd $REPO_DIR

echo "Run nodejs scripts"
npm run build

echo "Run merge script"
composer run build

echo "Run sudo-bot"

npm run sudo-bot-pr

rm -f $BOT_DIR_FILES/.env
