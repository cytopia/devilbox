#Autostart script, see here: https://devilbox.readthedocs.io/en/latest/autostart/custom-scripts-globally.html

croncmd="curl https://www.uconnectlabs.test/wp-content/wpmu-cron.php >/dev/null 2>&1"
cronjob="*/1 * * * * $croncmd"
#Add to crontab with no duplicate, see: https://stackoverflow.com/a/17975418/591830
( crontab -l | grep -v -F "$croncmd" ; echo "$cronjob" ) | crontab -

#Start the cron service
sudo service cron start

#Install WordPress Coding Standards and register the standards in PHP_CodeSniffer configuration.
WPCS_GIT_REPO=https://github.com/WordPress/WordPress-Coding-Standards.git
WPCS_LOCAL=/shared/httpd/uconnectlabs/tools/wpcs
git clone -b master "$WPCS_GIT_REPO" "$WPCS_LOCAL" 2> /dev/null || git -C "$WPCS_LOCAL" pull
sudo phpcs --config-set installed_paths "$WPCS_LOCAL,/shared/httpd/uconnectlabs" &
