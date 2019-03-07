:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _configure_php_xdebug_options:

************************
Xdebug options explained
************************

**Table of Contents**

.. contents:: :local:


Example
=======

Consider the following ``xdebug.ini`` as an example:

.. code-block:: ini
   :caption: xdebug.ini

   xdebug.default_enable=1
   xdebug.remote_enable=1
   xdebug.remote_handler=dbgp
   xdebug.remote_port=9000
   xdebug.remote_autostart=1
   xdebug.idekey="PHPSTORM"
   xdebug.remote_log=/var/log/php/xdebug.log

.. seealso:: |ext_lnk_xdebug_settings|

default_enable
--------------
By enabling this, stacktraces will be shown by default on an error event.
It is advisable to leave this setting set to 1.

remote_enable
-------------
This switch controls whether Xdebug should try to contact a debug client which is listening on the
host and port as set with the settings ``xdebug.remote_host`` and ``xdebug.remote_port``.
If a connection can not be established the script will just continue as if this setting was 0.

remote_handler
--------------
Can be either ``'php3'`` which selects the old PHP 3 style debugger output, ``'gdb'`` which enables
the GDB like debugger interface or ``'dbgp'`` - the debugger protocol. The DBGp protocol is the only
supported protocol.

**Note:** Xdebug 2.1 and later only support ``'dbgp'`` as protocol.

remote_port
-----------
The port to which Xdebug tries to connect on the remote host. Port ``9000`` is the default for both
the client and the bundled debugclient. As many clients use this port number, it is best to leave
this setting unchanged.

remote_autostart
----------------
Normally you need to use a specific HTTP GET/POST variable to start remote debugging (see
|ext_lnk_xdebug_remote_debugging|). When this setting is set to ``1``, Xdebug will always attempt
to start a remote debugging session and try to connect to a client, even if the GET/POST/COOKIE
variable was not present.

idekey
------
Controls which IDE Key Xdebug should pass on to the DBGp debugger handler. The default is based on
environment settings. First the environment setting DBGP_IDEKEY is consulted, then USER and as last
USERNAME. The default is set to the first environment variable that is found. If none could be found
the setting has as default ''. If this setting is set, it always overrides the environment variables.

.. important::
   Many IDE/editors require a specific value for ``xdebug.idekey``. Make sure you pay
   special attention to that variable when it comes to configuring your IDE/editor.

remote_log
----------
Keep the exact path of ``/var/log/php/xdebug.log``. You will then have the log file available
in the Devilbox log directory of the PHP version for which you have configured Xdebug.
