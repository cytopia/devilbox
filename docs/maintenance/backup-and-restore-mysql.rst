.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _backup_and_restore_mysql:

************************
Backup and restore MySQL
************************

Backup and restore will be necessary when you are going to switch MySQL versions.
Each version has its own data directory and is fully indepentend of other versions.
In case you want to switch to a different version, but still want to have your MySQL databases
present, you must first backup the databases of your current version and import them into the
new version.

There are multiple ways to backup and restore. Chose the one which is most convenient for you.


**Table of Contents**

.. contents:: :local:


Backup
======

There are many different options to backup your MySQL database including some for the command line
and some for using the Web interface. The recommended and fastest method is to use
``mysqldump-secure``, as it will also add info files (\*.info) to each database recording checksums,
dump date, dump options as well as the server version it came from.


Mysqldump-secure
----------------

|ext_lnk_tool_mysqldump_secure|  is bundled, setup and ready to use in every PHP container.
You can run it without any arguments and it will dump each available database as a
separated compressed file. Backups will be located in ``./backups/mysql/`` inside the Devilbox
git directory or in ``/shared/backups/mysql/`` inside the PHP container.

To have your backups in place is just three commands away:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Run mysqldump-secure
   devilbox@php-7.1.6 in /shared/httpd $ mysqldump-secure

   [INFO]  (OPT): Logging enabled
   [INFO]  (OPT): MySQL SSL connection disabled
   [INFO]  (OPT): Compression enabled
   [INFO]  (OPT): Encryption disabled
   [INFO]  (OPT): Deletion disabled
   [INFO]  (OPT): Nagios log disabled
   [INFO]  (OPT): Info files enabled
   [INFO]  (SQL): 1/3 Skipping: information_schema (DB is ignored)
   [INFO]  (SQL): 2/3 Dumping:  mysql (0.66 MB)  1 sec (0.13 MB)
   [INFO]  (SQL): 3/3 Skipping: performance_schema (DB is ignored)
   [OK]    Finished successfully

List backups
^^^^^^^^^^^^

Let's see where to find the backups inside the PHP container:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Show directory output
   devilbox@php-7.1.6 in /shared/httpd $ ls -l /shared/backups/mysql/

   -rw-r--r-- 1 devilbox 136751 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz
   -rw-r--r-- 1 devilbox   2269 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz.info


Let's do the same again and see where to find the backups in the Devilbox git directory

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Show directory output
   host> ls -l backups/mysql/

   -rw-r--r-- 1 cytopia 136751 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz
   -rw-r--r-- 1 cytopia   2269 Jun 17 13:31 2017-06-17_13-31__mysql.sql.gz.info

``*.info`` files
^^^^^^^^^^^^^^^^

The ``*.info`` file will hold many useful information in case you need to debug any problems
occured during backups. Let's have a look at one of them:

.. code-block:: bash

   host> cat ./backups/mysql/2017-06-17_13-31__mysql.sql.gz.info

.. code-block:: ini
   :caption: 2017-06-17_13-31__mysql.sql.gz.info

   ; mysqldump-secure backup record
   ; Do not alter this file!
   ; Creation of this file can be turned off via config file.

   ; ============================================================
   ; = Local system information
   ; ============================================================
   [mysqldump-secure]
   version    = /usr/local/bin/mysqldump-secure (0.16.3)
   vdate      = 2016-08-18
   config     = /etc/mysqldump-secure.conf

   [system]
   uname      = Linux 4.4.0-79-generic
   hostname   =
   user       = devilbox
   group      = devilbox

   [tools]
   mysqldump  = /usr/bin/mysqldump (10.14 Distrib 5.5.52-MariaDB) [for Linux (x86_64)]
   mysql      = /usr/bin/mysql (15.1 Distrib 5.5.52-MariaDB) [for Linux (x86_64) using readline 5.1]
   compressor = /usr/bin/gzip (gzip 1.5)
   encryptor  = Not used

   ; ============================================================
   ; = Database / File information
   ; ============================================================
   [database]
   db_name    = mysql
   db_size    = 687326 Bytes (0.66 MB)
   tbl_cnt    = 30

   [file]
   file_path  = /shared/backups/mysql
   file_name  = 2017-06-17_13-31__mysql.sql.gz
   file_size  = 136751 Bytes (0.13 MB)
   file_chmod = 0644
   file_owner = devilbox
   file_group = devilbox
   file_mtime = 1497699116 (2017-06-17 13:31:56 CEST [+0200])
   file_md5   = 8d1a6c38f81c691bc4b490e7024a4f72
   file_sha   = 11fb85282ea866dfc69d29dc02a0418bebfea30e7e566c3c588a50987aceac2f

   ; ============================================================
   ; = Dump procedure information
   ; ============================================================
   [mysqldump]
   encrypted  = 0
   compressed = 1
   arguments  = --opt --default-character-set=utf8 --events --triggers --routines --hex-blob --complete-insert --extended-insert --compress --lock-tables  --skip-quick
   duration   = 1 sec

   [compression]
   compressor = gzip
   arguments  = -9 --stdout

   [encryption]
   encryptor  =
   algorithm  =
   pubkey     =

   ; ============================================================
   ; = Server information
   ; ============================================================
   [connection]
   protocol   = mysql via TCP/IP
   secured    = No SSL
   arguments  = --defaults-file=/etc/mysqldump-secure.cnf

   [server]
   hostname   = 001b3750b549
   port       = 3306
   replica    = master
   version    = MariaDB 10.1.23-MariaDB MariaDB Server


mysqldump
---------

|ext_lnk_tool_mysqldump| is bundled with each PHP container and ready to use.
To backup a database named ``my_db_name`` follow the below listed
example which shows you how to do that from within the PHP container:

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the backup
   devilbox@php-7.1.6 in /shared/httpd $ mysqldump -h mysql -u root -p my_db_name > /shared/backups/mysql/my_db_name.sql

To find out more about the configuration and options of mysqldump, visit its project page under:
|ext_lnk_tool_mysqldump|


phpMyAdmin
----------

If you do not like to use the command line for backups, you can use |ext_lnk_tool_phpmyadmin|.
It comes bundled with the devilbox intranet.


Adminer
-------

If you do not like to use the command line for backups, you can use |ext_lnk_tool_adminer| .
It comes bundled with the devilbox intranet.


Restore
=======

mysql
-----

In order to restore or import mysql databases on the command line, you need to use the ``mysql``
binary. Here are a few examples for different file types:

``*.sql`` file
^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ mysql -h mysql -u root -p my_db_name < /shared/backups/mysql/my_db_name.sql



``*.sql.gz`` file
^^^^^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ zcat /shared/backups/mysql/my_db_name.sql.gz | mysql -h mysql -u root -p my_db_name


``*.sql.tar.gz`` file
^^^^^^^^^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to the Devilbox directory
   host> cd path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # Start the import
   devilbox@php-7.1.6 in /shared/httpd $ tar xzOf /shared/backups/mysql/my_db_name.sql.tar.gz | mysql -h mysql -u root -p my_db_name


phpMyAdmin
----------

|ext_lnk_tool_phpmyadmin| supports importing many different formats out-of-the-box.
Simply select the compressed or uncompressed file and press ``Go`` in the import section of
the web interface.


Adminer
-------

|ext_lnk_tool_adminer| supports importing of plain (``*.sql``) or gzipped compressed
(``*.sql.gz``) files out-of-the-box. Simply select the compressed or uncompressed file and press
``Execute`` in the import section of the web interface.
