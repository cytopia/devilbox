.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _reverse_proxy_with_https:

************************
Reverse Proxy with HTTPS
************************

Imagine you have started an application within the PHP container that creates a listening port
(e.g.: NodeJS). This will now only listen on the PHP container and you would have to adjust
the docker-compose.yml definition in order to have that port available outside to your host OS.

Alternatively, there is a simple way to reverse proxy it to the already running web server and even
make use of the available HTTPS feature.

.. seealso::
   **Read more about how to autostart applications:**

   * :ref:`custom_scripts_per_php_version` (individually for different PHP versions)
   * :ref:`custom_scripts_globally` (equal for all PHP versions)


**Table of Contents**

.. contents:: :local:


Walkthrough
===========

Assumption
----------

Let's imagine you have started an application in the PHP container with the following settings:

* :ref:`env_TLD_SUFFIX`: ``loc``
* :ref:`getting_started_directory_overview_project_directory` inside PHP container: ``/shared/httpd/my-app``
* :ref:`env_httpd_datadir` on the host: ``./data/www``
* :ref:`env_httpd_template_dir`: ``.devilbox``
* Listening port: ``8081``
* Listening host: ``php`` (any of the PHP container)
* Resulting vhost name: ``my-app.loc``


Copy vhost-gen templates
------------------------

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

For this example we will copy all ``*-example-rproxy`` files into ``/shared/httpd/my-app/.devilbox``
to ensure this will work with all web servers.

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cp cfg/vhost-gen/apache22.yml-example-rproxy data/www/my-app/.devilbox/apache22.yml
   host> cp cfg/vhost-gen/apache24.yml-example-rproxy data/www/my-app/.devilbox/apache24.yml
   host> cp cfg/vhost-gen/nginx.yml-example-rproxy data/www/my-app/.devilbox/nginx.yml


Adjust port
-----------

By default, all vhost-gen templates will forward requests to port ``8000`` into the PHP container.
Our current example however uses port ``8081``, so we must change that accordingly for all three
templates.

Adjust Apache 2.2 template
^^^^^^^^^^^^^^^^^^^^^^^^^^

Open the ``apache22.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-app/.devilbox/apache22.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``8081``

.. code-block:: yaml
   :caption: data/www/my-app/.devilbox/apache22.yml
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
         ProxyPass / http://php:8081/
         ProxyPassReverse / http://php:8081/

   # ... more lines below ... #

Adjust Apache 2.4 template
^^^^^^^^^^^^^^^^^^^^^^^^^^

Open the ``apache24.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-app/.devilbox/apache24.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``8081``

.. code-block:: yaml
   :caption: data/www/my-app/.devilbox/apache24.yml
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
         ProxyPass / http://php:8081/
         ProxyPassReverse / http://php:8081/

   # ... more lines below ... #

Adjust Nginx template
^^^^^^^^^^^^^^^^^^^^^

Open the ``nginx.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-app/.devilbox/nginx.yml


Find the lines with ``proxy_pass`` and change the port from ``8000`` to ``8081``

.. code-block:: yaml
   :caption: data/www/my-app/.devilbox/nginx.yml
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
           proxy_pass http://php:8081;
         }

   # ... more lines below ... #



Restart the Devilbox
--------------------

Now for the changes to take affect, simply restart the Devilbox (or start if not yet running):


.. code-block:: bash

   host> cd /path/to/devilbox

   # Stop the Devilbox
   host> docker-compose stop
   host> docker-compose rm -f

   # Start the Devilbox (PHP and HTTPD container only)
   host> docker-compose up -d php httpd bind


Start your application
----------------------

Enter the PHP container and start your application which creates the listening port in port ``8081``.


.. seealso::
   This can also be automated to happen automatically during ``docker-compose up`` via:

   * :ref:`custom_scripts_per_php_version` (individually for different PHP versions)
   * :ref:`custom_scripts_globally` (equal for all PHP versions)
   * Example: :ref:`autostarting_nodejs_apps`


Visit your project
------------------

That's it, your service application will now be available via:

* http://my-app.loc

It will also be available on HTTPS. This is by default and automatically:

* https://my-app.loc

.. seealso:: :ref:`setup_valid_https`


And is even shown as a project in the Devilbox intranet:

* http://localhost/vhosts.php
* https://localhost/vhosts.php
