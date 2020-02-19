.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _reverse_proxy_with_custom_docker:

*******************************
Reverse Proxy for custom Docker
*******************************


Imagine you have added a custom service container to the Devilbox which has a project that
is available via http on a very specific port in that container.

You do not want to expose this port to the host system, but rather want to make it available
via the bundled web server and also be able to see it on the Devilbox intranet vhost section.

Additionally you want the project to make use of the same DNS naming scheme and also have HTTPS
for it.

You can easily achieve this by setting up a reverse proxy for it.


.. seealso:: :ref:`add_your_own_docker_image`


**Table of Contents**

.. contents:: :local:


Walkthrough
===========

Assumption
----------

Let's imagine you have added a custom Python Docker image to the Devilbox which starts up a Django
application listening on port ``3000``.

* :ref:`env_TLD_SUFFIX`: ``loc``
* Desired DNS name: ``my-python.loc``
* :ref:`env_httpd_datadir` on the host: ``./data/www``
* :ref:`env_httpd_template_dir`: ``.devilbox``
* Listening port: ``3000``
* Listening host: ``python`` (hostname of the Python container)


Create *virtual* directory
--------------------------

In order to create a reverse proxy for that custom container, you must add a *virtual* project
directory without any data in it. This directory is purely for the purpose of determining the
DNS name and for having the vhost-gen configuration in.

Navigate to the :ref:`env_httpd_datadir` directory and create your project

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cd /path/to/devilbox/data/www

   # Create the project directory
   host> mkdir my-python

   # Create the htdocs directory (to have a valid project for the Intranet page)
   host> mkdir my-python/htdocs


   # Create the vhost-gen directory (to be apply to apply custom templates)
   host> mkdir my-python/.devilbox

This part is now sufficient to have the project visible on the Devilbox intranet.


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

For this example we will copy all ``*-example-rproxy`` files into ``/shared/httpd/my-python/.devilbox``
to ensure this will work with all web servers.

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cp cfg/vhost-gen/apache22.yml-example-rproxy data/www/my-python/.devilbox/apache22.yml
   host> cp cfg/vhost-gen/apache24.yml-example-rproxy data/www/my-python/.devilbox/apache24.yml
   host> cp cfg/vhost-gen/nginx.yml-example-rproxy data/www/my-python/.devilbox/nginx.yml


Adjust port
-----------

By default, all vhost-gen templates will forward requests to port ``8000`` into the PHP container.
Our current example however uses port ``3000`` and host ``python``, so we must change that accordingly for all three
templates.

Adjust Apache 2.2 template
^^^^^^^^^^^^^^^^^^^^^^^^^^

Open the ``apache22.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-python/.devilbox/apache22.yml

Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``3000`` and host ``php`` to ``python``:

.. code-block:: yaml
   :caption: data/www/my-python/.devilbox/apache22.yml
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
         ProxyPass / http://python:3000/
         ProxyPassReverse / http://python:3000/

   # ... more lines below ... #

Adjust Apache 2.4 template
^^^^^^^^^^^^^^^^^^^^^^^^^^

Open the ``apache24.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-python/.devilbox/apache24.yml

Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``3000`` and host ``php`` to ``python``:

.. code-block:: yaml
   :caption: data/www/my-python/.devilbox/apache24.yml
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
         ProxyPass / http://python:3000/
         ProxyPassReverse / http://python:3000/

   # ... more lines below ... #

Adjust Nginx template
^^^^^^^^^^^^^^^^^^^^^

Open the ``nginx.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-python/.devilbox/nginx.yml

Find the line with ``proxy_pass`` and change the port from ``8000``
to ``3000`` and host ``php`` to ``python``:

.. code-block:: yaml
   :caption: data/www/my-python/.devilbox/nginx.yml
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
           proxy_pass http://python:3000;
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

   # Start the Devilbox (Your Python container and the PHP and HTTPD container only)
   host> docker-compose up -d php httpd bind python


Visit your project
------------------

That's it, your service application will now be available via:

* http://my-python.loc

It will also be available on HTTPS. This is by default and automatically:

* https://my-python.loc

.. seealso:: :ref:`setup_valid_https`


And is even shown as a project in the Devilbox intranet:

* http://localhost/vhosts.php
* https://localhost/vhosts.php
