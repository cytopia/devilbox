.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_reverse_proxy_nodejs:

**************************
Setup reverse proxy NodeJS
**************************

This example will walk you through creating a NodeJS hello world application, which is started
automatically on ``docker-compose up`` via |ext_lnk_tool_pm2|, will be proxied to the web server and can be reached via valid HTTPS.


.. note::
   It is also possible to attach a leight-weight NodeJS container to the Devilbox instead of running
   this in the PHP container. See here for details: :ref:`reverse_proxy_with_custom_docker`


**Table of Contents**

.. contents:: :local:

Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+---------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                 |
+==============+==========================+=============+============+=============================================+
| my-node      | /shared/httpd/my-node    | -           | loc        | http://my-node.loc |br| https://my-node.loc |
+--------------+--------------------------+-------------+------------+---------------------------------------------+

Additionally we will set the listening port of the NodeJS appliation to ``4000`` inside the PHP container.

We also want NodeJS running regardless of which PHP container will bestarted (global autostart).

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.

Walk through
============

It will be ready in nine simple steps:

1. Enter the PHP container
2. Create a new VirtualHost directory
3. Create NodeJS hello world application
4. Create *virtual* docroot directory
5. Add reverse proxy vhost-gen config files
6. Create autostart script
7. Setup DNS record
8. Restart the Devilbox
9. Visit http://my-node.loc in your browser


1. Enter the PHP container
--------------------------

All work will be done inside the PHP container as it provides you with all required command line
tools.

Navigate to the Devilbox git directory and execute ``shell.sh`` (or ``shell.bat`` on Windows) to
enter the running PHP container.

.. code-block:: bash

   host> ./shell.sh

.. seealso::
   * :ref:`enter_the_php_container`
   * :ref:`work_inside_the_php_container`
   * :ref:`available_tools`


2. Create new vhost directory
-----------------------------

The vhost directory defines the name under which your project will be available. |br|
( ``<vhost dir>.TLD_SUFFIX`` will be the final URL ).

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-node

.. seealso:: :ref:`env_tld_suffix`


3. Create NodeJS application
----------------------------

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-node

   # Create a directory which will hold the source code
   devilbox@php-7.0.20 in /shared/httpd/my-node $ mkdir src

   # Create the index.js file with your favourite editor
   devilbox@php-7.0.20 in /shared/httpd/my-node/src $ vi index.js

.. code-block:: javascript
   :caption: index.js

   // Load the http module to create an http server.
   var http = require('http');

   // Configure our HTTP server to respond with Hello World to all requests.
   var server = http.createServer(function (request, response) {
     response.writeHead(200, {"Content-Type": "text/plain"});
     response.end("Hello World\n");
   });

   // Listen on port 4000
   server.listen(4000);

4. Create *virtual* docroot directory
-------------------------------------

Every project for the Devilbox requires a ``htdocs`` directory present inside the project dir.
For a reverse proxy this is not of any use, but rather only for the Intranet vhost page to stop
complaining about the missing ``htdocs`` directory. So that's why this is only a *virtual* directory
which will not hold any data.

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-node

   # Create the docroot directory
   devilbox@php-7.0.20 in /shared/httpd/my-node $ mkdir htdocs

.. seealso:: :ref:`env_httpd_docroot_dir`


5. Add reverse proxy vhost-gen config files
-------------------------------------------

5.1 Create vhost-gen template directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Before we can copy the vhost-gen templates, we must create the ``.devilbox`` template directory
inside the project directory.

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-node

   # Create the .devilbox template directory
   devilbox@php-7.0.20 in /shared/httpd/my-node $ mkdir .devilbox


.. seealso:: :ref:`env_httpd_template_dir`

5.2 Copy vhost-gen templates
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Now we can copy and adjust the vhost-gen reverse proxy files for Apache 2.2, Apache 2.4 and Nginx.


The reverse vhost-gen templates are available in ``cfg/vhost-gen``:

.. code-block:: bash
   :emphasize-lines: 4,6,8

   host> tree -L 1 cfg/vhost-gen/

   cfg/vhost-gen/
   ├── apache22.yml-example-rproxy
   ├── apache22.yml-example-vhost
   ├── apache24.yml-example-rproxy
   ├── apache24.yml-example-vhost
   ├── nginx.yml-example-rproxy
   ├── nginx.yml-example-vhost
   └── README.md

   0 directories, 7 files

For this example we will copy all ``*-example-rproxy`` files into ``/shared/httpd/my-node/.devilbox``
to ensure this will work with all web servers.

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cp cfg/vhost-gen/apache22.yml-example-rproxy data/www/my-node/.devilbox/apache22.yml
   host> cp cfg/vhost-gen/apache24.yml-example-rproxy data/www/my-node/.devilbox/apache24.yml
   host> cp cfg/vhost-gen/nginx.yml-example-rproxy data/www/my-node/.devilbox/nginx.yml


5.3 Adjust ports
^^^^^^^^^^^^^^^^

By default, all vhost-gen templates will forward requests to port ``8000`` into the PHP container.
Our current example however uses port ``4000``, so we must change that accordingly for all three
templates.

5.3.1 Adjust Apache 2.2 template
""""""""""""""""""""""""""""""""

Open the ``apache22.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-node/.devilbox/apache22.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``4000``

.. code-block:: yaml
   :caption: data/www/my-node/.devilbox/apache22.yml
   :emphasize-lines: 16,17

   # ... more lines above ... #

   ###
   ### Basic vHost skeleton
   ###
   vhost: |
     <VirtualHost __DEFAULT_VHOST__:__PORT__>
         ServerName   __VHOST_NAME__

         CustomLog  "__ACCESS_LOG__" combined
         ErrorLog   "__ERROR_LOG__"

         # Reverse Proxy definition (Ensure to adjust the port, currently '8000')
         ProxyRequests On
         ProxyPreserveHost On
         ProxyPass / http://php:4000/
         ProxyPassReverse / http://php:4000/

   # ... more lines below ... #

5.3.2 Adjust Apache 2.4 template
""""""""""""""""""""""""""""""""

Open the ``apache24.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-node/.devilbox/apache24.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``4000``

.. code-block:: yaml
   :caption: data/www/my-node/.devilbox/apache24.yml
   :emphasize-lines: 16,17

   # ... more lines above ... #

   ###
   ### Basic vHost skeleton
   ###
   vhost: |
     <VirtualHost __DEFAULT_VHOST__:__PORT__>
         ServerName   __VHOST_NAME__

         CustomLog  "__ACCESS_LOG__" combined
         ErrorLog   "__ERROR_LOG__"

         # Reverse Proxy definition (Ensure to adjust the port, currently '8000')
         ProxyRequests On
         ProxyPreserveHost On
         ProxyPass / http://php:4000/
         ProxyPassReverse / http://php:4000/

   # ... more lines below ... #

5.3.3 Adjust Nginx template
"""""""""""""""""""""""""""

Open the ``nginx.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-node/.devilbox/nginx.yml


Find the lines with ``proxy_pass`` and change the port from ``8000`` to ``4000``

.. code-block:: yaml
   :caption: data/www/my-node/.devilbox/nginx.yml
   :emphasize-lines: 18

   # ... more lines above ... #

   ###
   ### Basic vHost skeleton
   ###
   vhost: |
     server {
         listen       __PORT____DEFAULT_VHOST__;
         server_name  __VHOST_NAME__;

         access_log   "__ACCESS_LOG__" combined;
         error_log    "__ERROR_LOG__" warn;

         # Reverse Proxy definition (Ensure to adjust the port, currently '8000')
         location / {
           proxy_set_header Host $host;
           proxy_set_header X-Real-IP $remote_addr;
           proxy_pass http://php:4000;
         }

   # ... more lines below ... #

6. Create autostart script
--------------------------

For NodeJS applications, the Devilbox already bundles an autostart template which you can use
and simply just add the path of your NodeJS application. This template does nothing by default
as its file name does not end by ``.sh``. So let's have a look at the template from ``autostart/run-node-js-projects.sh-example``.
The location where you will have to add your path is highlighted:

.. literalinclude:: ../../autostart/run-node-js-projects.sh-example
   :caption: autostart/run-node-js-projects.sh-example
   :language: bash
   :emphasize-lines: 15


So in order to proceed copy this file inside the ``autostart/`` directory of the Devilbox git directory
to a new file ending by ``.sh``

.. code-block:: bash

   host> cd /path/to/devilbox

   # Navigate to the autostart directory
   host> cd autostart

   # Copy the template
   host> cp run-node-js-projects.sh-example run-node-js-projects.sh

   # Adjust the template and add your path:
   host> vi run-node-js-projects.sh


.. code-block:: bash
   :caption: autostart/run-node-js-projects.sh
   :emphasize-lines: 7

   # ... more lines above ... #

   # Add the full paths of your Nodejs projects startup files into this array
   # Each project separated by a newline and enclosed in double quotes. (No commas!)
   # Paths are internal paths inside the PHP container.
   NODE_PROJECTS=(
       "/shared/httpd/my-node/js/index.js"
   )

   # ... more lines below ... #

.. seealso::

   * :ref:`custom_scripts_per_php_version` (individually for different PHP versions)
   * :ref:`custom_scripts_globally` (equal for all PHP versions)
   * :ref:`autostarting_nodejs_apps`

7. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-node.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


8. Restart the Devilbox
-----------------------

Now for those changes to take affect, you will have to restart the Devilbox.

.. code-block:: bash

   host> cd /path/to/devilbox

   # Stop the Devilbox
   host> docker-compose down
   host> docker-compose rm -f

   # Start the Devilbox
   host> docker-compose up -d php httpd bind


9. Open your browser
--------------------

All set now, you can visit http://my-node.loc or https://my-node.loc in your browser.
The NodeJS application has been started up automatically and the reverse proxy will direct all
requests to it.


Managing NodeJS
===============

If you have never worked with |ext_lnk_tool_pm2|, I suggest to visit their website and get familiar
with the available commands. A quick guide is below:



.. code-block:: bash

   # Navigate to Devilbox git directory
   host> cd /path/to/devilbox

   # Enter the PHP container
   host> ./shell.sh

   # List your running NodeJS apps
   devilbox@php-7.0.20 in /shared/httpd $ pm2 list

   ┌──────────┬────┬─────────┬──────┬──────┬────────┬─────────┬────────┬─────┬───────────┬──────────┬──────────┐
   │ App name │ id │ version │ mode │ pid  │ status │ restart │ uptime │ cpu │ mem       │ user     │ watching │
   ├──────────┼────┼─────────┼──────┼──────┼────────┼─────────┼────────┼─────┼───────────┼──────────┼──────────┤
   │ index    │ 0  │ N/A     │ fork │ 1906 │ online │ 0       │ 42h    │ 0%  │ 39.7 MB   │ devilbox │ disabled │
   └──────────┴────┴─────────┴──────┴──────┴────────┴─────────┴────────┴─────┴───────────┴──────────┴──────────┘


Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
