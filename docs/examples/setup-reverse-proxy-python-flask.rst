.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _example_setup_reverse_proxy_python_flask:

********************************
Setup reverse proxy Python Flask
********************************

This example will walk you through adding a version specific Python Flask docker container,
creating a simple Flask hello world application and have its requirements specified in
``requirements.txt`` automatically installed. Once setup, your application will be ready via
``docker-compose up``, proxied to the web server and can be reached via valid HTTPS.


.. note::
   This example is using an additional Docker image, so you are able to specify any Python version
   you like and even be able to add multiple Docker images with different versions.


**Table of Contents**

.. contents:: :local:

Overview
========

The following configuration will be used:

+--------------+--------------------------+-------------+------------+-----------------------------------------------+
| Project name | VirtualHost directory    | Database    | TLD_SUFFIX | Project URL                                   |
+==============+==========================+=============+============+===============================================+
| my-flask     | /shared/httpd/my-flask   | -           | loc        | http://my-flask.loc |br| https://my-flask.loc |
+--------------+--------------------------+-------------+------------+-----------------------------------------------+

.. note::
   * Inside the Devilbox PHP container, projects are always in ``/shared/httpd/``.
   * On your host operating system, projects are by default in ``./data/www/`` inside the
     Devilbox git directory. This path can be changed via :ref:`env_httpd_datadir`.

The following Devilbox configuration is required:

+--------------+------------------------------------------------------------------------------------------------+
| Service      | Implications                                                                                   |
+==============+================================================================================================+
| Webserver    | Reverse proxy vhost-gen template need to be applied                                            |
+--------------+------------------------------------------------------------------------------------------------+
| Python Flask | Docker Compose override file must be applied.                                                  |
+--------------+------------------------------------------------------------------------------------------------+
| ``.env``     | ``FLASK_PROJECT`` variable must be declared and set.                                           |
+--------------+------------------------------------------------------------------------------------------------+
| ``.env``     | ``PYTHON_VERSION`` variable can be declared and set.                                           |
+--------------+------------------------------------------------------------------------------------------------+

Additionally we will set the listening port of the Flask appliation to ``3000``.

.. seealso::
   For a detailed overview about the Compose file see: :ref:`custom_container_enable_python_flask`


Walk through
============

It will be ready in ten simple steps:

1. Configure Python Flask project name and versoin
2. Enter the PHP container
3. Create a new VirtualHost directory
4. Create Flask hello world application
5. Symlink *virtual* docroot directory
6. Add reverse proxy vhost-gen config files
7. Copy Python Flask compose file
8. Setup DNS record
9. Restart the Devilbox
10. Visit http://my-flask.loc in your browser


1. Configure Python Flask project name and version
--------------------------------------------------

The Python Flask container will only serve a single project. In order for it to know where
to find your project, you will have to add an environment variable (``FLASK_PROJECT``),
telling it what the directory name of your project is.

Additionally you can define the Python version (``PYTHON_VERSION``)  under which you want to run
your Flask project.

.. seealso:: Available Python versions can be seen here: https://github.com/devilbox/docker-python-flask

Add the following variable to the very end of your ``.env`` file:

.. code-block:: bash
   :caption: .env

   FLASK_PROJECT=my-flask

   #PYTHON_VERSION=2.7
   #PYTHON_VERSION=3.5
   #PYTHON_VERSION=3.6
   #PYTHON_VERSION=3.7
   PYTHON_VERSION=3.8


2. Enter the PHP container
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


3. Create new VirtualHost directory
-----------------------------------

The vhost directory defines the name under which your project will be available. |br|
( ``<vhost dir>.TLD_SUFFIX`` will be the final URL ).

.. code-block:: bash

   devilbox@php-7.0.20 in /shared/httpd $ mkdir my-flask

.. seealso:: :ref:`env_tld_suffix`


4. Create Flask hello world application
---------------------------------------

4.1 Add your code
^^^^^^^^^^^^^^^^^

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-flask

   # Create a directory which will hold the source code
   devilbox@php-7.0.20 in /shared/httpd/my-flask $ mkdir app

   # Create the main.py file with your favourite editor
   devilbox@php-7.0.20 in /shared/httpd/my-flask/app $ vi main.py

.. code-block:: python
   :caption: main.py

   """Flask example application."""
   from flask import Flask

   app = Flask(__name__)

   @app.route("/")
   def index():
       """Serve the default index page."""
       return "Hello World!"


   if __name__ == "__main__":
       """Ensure Flask listens on all interfaces."""
       app.run(host='0.0.0.0')

4.2 Add dependencies
^^^^^^^^^^^^^^^^^^^^

You can optionally add a ``requirements.txt`` file which will be read during startup. The Python
Flask container will then automatically install all Python libraries specified in that file.

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-flask

   # Create and open the file with your favourite editor
   devilbox@php-7.0.20 in /shared/httpd/my-flask $ vi requirements.txt

.. code-block:: ini
   :caption: data/www/my-flask/requirements.txt

   requests


5. Symlink *virtual* docroot directory
--------------------------------------

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-flask

   # Create the docroot directory
   devilbox@php-7.0.20 in /shared/httpd/my-flask $ ln -s app htdocs

.. seealso:: :ref:`env_httpd_docroot_dir`


6. Add reverse proxy vhost-gen config files
-------------------------------------------

6.1 Create vhost-gen template directory
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Before we can copy the vhost-gen templates, we must create the ``.devilbox`` template directory
inside the project directory.

.. code-block:: bash

   # Navigate to your project directory
   devilbox@php-7.0.20 in /shared/httpd $ cd my-flask

   # Create the .devilbox template directory
   devilbox@php-7.0.20 in /shared/httpd/my-flask $ mkdir .devilbox


.. seealso:: :ref:`env_httpd_template_dir`

6.2 Copy vhost-gen templates
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

For this example we will copy all ``*-example-rproxy`` files into ``data/www/my-flask/.devilbox/``
(Inside container: ``/shared/httpd/my-flask/.devilbox``) to ensure this will work with all web servers.

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cp cfg/vhost-gen/apache22.yml-example-rproxy data/www/my-flask/.devilbox/apache22.yml
   host> cp cfg/vhost-gen/apache24.yml-example-rproxy data/www/my-flask/.devilbox/apache24.yml
   host> cp cfg/vhost-gen/nginx.yml-example-rproxy data/www/my-flask/.devilbox/nginx.yml


6.3 Adjust ports and backend
^^^^^^^^^^^^^^^^^^^^^^^^^^^^

By default, all vhost-gen templates will forward requests to port ``8000`` into the PHP container.
Our current example however uses port ``3000`` and backend IP ``172.16.238.250`` (as defined
in the Flask docker compose override file), so we must change that accordingly for all three
templates.

6.3.1 Adjust Apache 2.2 template
""""""""""""""""""""""""""""""""

Open the ``apache22.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-flask/.devilbox/apache22.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``3000`` as well as the backend server from ``php`` to ``172.16.238.250``.

.. code-block:: yaml
   :caption: data/www/my-flask/.devilbox/apache22.yml
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
         ProxyPass / http://172.16.238.250:3000/
         ProxyPassReverse / http://172.16.238.250:3000/

   # ... more lines below ... #

6.3.2 Adjust Apache 2.4 template
""""""""""""""""""""""""""""""""

Open the ``apache24.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-flask/.devilbox/apache24.yml


Find the two lines with ``ProxyPass`` and ``ProxyPassReverse`` and change the port from ``8000``
to ``3000``

.. code-block:: yaml
   :caption: data/www/my-flask/.devilbox/apache24.yml
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
         ProxyPass / http://172.16.238.250:3000/
         ProxyPassReverse / http://172.16.238.250:3000/

   # ... more lines below ... #

6.3.3 Adjust Nginx template
"""""""""""""""""""""""""""

Open the ``nginx.yml`` vhost-gen template in your project:

.. code-block:: bash

   host> cd /path/to/devilbox
   host> vi data/www/my-flask/.devilbox/nginx.yml


Find the lines with ``proxy_pass`` and change the port from ``8000`` to ``3000``

.. code-block:: yaml
   :caption: data/www/my-flask/.devilbox/nginx.yml
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
           proxy_pass http://172.16.238.250:3000;
         }

   # ... more lines below ... #


7. Copy Python Flask compose file
---------------------------------

Python Flask comes with its own Docker container and can be added to the Devilbox stack via
the ``docker-compose.override.yml`` file. A fully functional template already exists in the
``compose/`` directory. All you will have to do is copy it over.

.. code-block:: bash

   host> cd /path/to/devilbox
   host> cp compose/docker-compose.override.yml-python-flask.yml docker-compose.override.yml

.. seealso:: :ref:`docker_compose_override_yml`


8. DNS record
-------------

If you **have** Auto DNS configured already, you can skip this section, because DNS entries will
be available automatically by the bundled DNS server.

If you **don't have** Auto DNS configured, you will need to add the following line to your
host operating systems ``/etc/hosts`` file (or ``C:\Windows\System32\drivers\etc`` on Windows):

.. code-block:: bash
   :caption: /etc/hosts

   127.0.0.1 my-flask.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`
   * :ref:`setup_auto_dns`


9. Restart the Devilbox
-----------------------

Now for those changes to take affect, you will have to restart the Devilbox.

.. code-block:: bash

   host> cd /path/to/devilbox

   # Stop the Devilbox
   host> docker-compose down
   host> docker-compose rm -f

   # Start the Devilbox
   host> docker-compose up -d php httpd bind flask1


10. Open your browser
---------------------

All set now, you can visit http://my-flask.loc or https://my-flask.loc in your browser.
The Python Flask application has been started up automatically and the reverse proxy will direct all
requests to it.



Next steps
==========

.. include:: /_includes/snippets/examples/next-steps.rst
