#Autostart script, see here: https://devilbox.readthedocs.io/en/latest/autostart/custom-scripts-globally.html

croncmd="curl https://www.uconnectlabs.test/wp-content/wpmu-cron.php >/dev/null 2>&1"
cronjob="*/1 * * * * $croncmd"
#Add to crontab with no duplicate, see: https://stackoverflow.com/a/17975418/591830
( crontab -l | grep -v -F "$croncmd" ; echo "$cronjob" ) | crontab -

#Start the cron service
sudo service cron start
