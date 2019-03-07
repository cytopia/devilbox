.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _showcase_over_the_internet:

**************************
Showcase over the internet
**************************

**Table of Contents**

.. contents:: :local:


Why
===

Sometimes it is just convinient to make your local project available over the internet to quickly
showcase your current work to a customer. Instead of having to deploy it somewhere and even be able
to live code during the showcase the Devilbox provides an easy way to accomplish exactly this via
Ngrok.


How
===

First you want to add Ngrok to the Devilbox stack via its pre-defined Docker Compose override file.

.. seealso::    * :ref:`custom_container_enable_ngrok`

Once you have followed the above documentation everything works with default settings. To actually
customize and choose the virtual host to expose you will need to alter the ``NGROK_HTTP_TUNNELS``
.env variable.

How this can be done exactly will be shown in a couple of examples below.

Examples
--------

Recall the following formats for the variable:

* ``<domain.tld>:<addr>:<port>``
* ``<domain1.tld>:<addr>:<port>,<domain2.tld>:<addr>:<port>``

.. note:: Even more than two tunnels are supported, but this will again depend on your Ngrok license.

Where each individual part consists of:

* ``<domain.tld>`` is the virtual hostname that you want to serve via Ngrok
* ``<addr>`` is the hostname or IP address of the web server
* ``<port>`` is the port on which the web server is reachable via HTTP

Expose ``my-project.loc`` via web server
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* ``<domain.tld>``: my-project.loc
* ``<addr>``: httpd
* ``<port>``: httpd80

So the resulting ``.env`` value will be:

.. code-block:: bash

   NGROK_HTTP_TUNNELS=my-project.loc:httpd:80

Expose ``my-project.loc`` via Varnish
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

* ``<domain.tld>``: my-project.loc
* ``<addr>``: varnish
* ``<port>``: 6081

So the resulting ``.env`` value will be:

.. code-block:: bash

   NGROK_HTTP_TUNNELS=my-project.loc:varnish:6081

Expose ``my-project.loc`` via HAProxy
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
* ``<domain.tld>``: my-project.loc
* ``<addr>``: haproxy
* ``<port>``: 80

So the resulting ``.env`` value will be:

.. code-block:: bash

   NGROK_HTTP_TUNNELS=my-project.loc:haproxy:80


Expose ``my-project.loc`` and ``website1.loc`` via web server
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. note:: Exposing more than one vhost will require a pro enough license from Ngrok.

* ``<domain.tld>``: my-project.loc
* ``<addr>``: httpd
* ``<port>``: 80

and

* ``<domain.tld>``: website1.loc
* ``<addr>``: httpd
* ``<port>``: 80


So the resulting ``.env`` value will be:

.. code-block:: bash

   NGROK_HTTP_TUNNELS=my-project.loc:httpd:80,website1.loc:httpd:80

Expose ``my-project.loc`` via web server and varnish
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

.. note:: Exposing more than one vhost will require a pro enough license from Ngrok.

* ``<domain.tld>``: my-project.loc
* ``<addr>``: httpd
* ``<port>``: 80

and


* ``<domain.tld>``: my-project.loc
* ``<addr>``: varnish
* ``<port>``: 6081

So the resulting ``.env`` value will be:

.. code-block:: bash

   NGROK_HTTP_TUNNELS=my-project.loc:httpd:80,my-project.loc:varnish:6081
