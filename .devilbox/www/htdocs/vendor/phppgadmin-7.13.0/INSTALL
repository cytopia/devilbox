phpPgAdmin Installation Guide
-----------------------------

1. Unpack your download

   If you've downloaded a tar.gz package, execute from a terminal:

   gunzip phpPgAdmin-*.tar.gz
   tar -xvf phpPgAdmin-*.tar

   Else, if you've downloaded a tar.bz2 package, execute from a terminal: 

   bunzip2 phpPgAdmin-*.tar.bz2
   tar -xvf phpPgAdmin-*.tar

   Else, if you've downloaded a zip package, execute from a terminal:

   unzip phpPgAdmin-*.zip

2. Configure phpPgAdmin

   edit phpPgAdmin/conf/config.inc.php

   If you mess up the configuration file, you can recover it from the
   config.inc.php-dist file.

3. Ensure the statistics collector is enabled in PostgreSQL.  phpPgAdmin will 
   display table, index performance, and usage statistics if you have enabled
   the PostgreSQL statistics collector.  While this is normally enabled by 
   default, to ensure it is running, make sure the following lines in your 
   postgresql.conf are uncommented: 

    track_activities
    track_counts 

4. Browse to the phpPgAdmin installation using a web browser.  You might
   need cookies enabled for phpPgAdmin to work.

5. IMPORTANT - SECURITY

   PostgreSQL by default does not require you to use a password to log in.
   We STRONGLY recommend that you enable md5 passwords for local connections
   in your pg_hba.conf, and set a password for the default superuser account.

   Due to the large number of phpPgAdmin installations that have not set
   passwords on local connections, there is now a configuration file
   option called 'extra_login_security', which is TRUE by default.  While
   this option is enabled, you will be unable to log in to phpPgAdmin as
   the 'root', 'administrator', 'pgsql' or 'postgres' users and empty passwords
   will not work.
   
   Once you are certain you have properly secured your database server, you
   can then disable 'extra_login_security' so that you can log in as your
   database administrator using the administrator password.
