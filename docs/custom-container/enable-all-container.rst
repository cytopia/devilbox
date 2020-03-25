.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _custom_container_enable_all_additional_container:

*******************************
Enable all additional container
*******************************

Besides providing basic LAMP/MEAN stack container, which are well integrated into the Devilbox
intranet, the Devilbox also ships additional pre-configured container that can easily be enabled.

.. seealso:: :ref:`docker_compose_override_yml_how_does_it_work`


**Table of Contents**

.. contents:: :local:


Available additional container
==============================

The following table shows you the currently additional available container:

.. include:: /_includes/snippets/additional-container.rst


Enable all additional container
===============================

Copy ``docker-compose.override.yml-all`` into the root of the Devilbox git directory.

.. code-block:: bash

   host> cp compose/docker-compose.override.yml-all docker-compose.override.yml

That's it, if you ``docker-compose up``, all container will be started. This however is not
adviced as it will eat up a lot of resources. You are better off by selectively specifying the
container you want to run.

.. seealso:: :ref:`start_the_devilbox`


Configure additional container
==============================

The additional container also provide many configuration options just as the default ones do.
That includes, but is not limited to:

* Image version
* Exposed ports
* Mount points
* And various container specific settings

In order to fully customize each container, refer to their own documentation section:

.. seealso::
   * :ref:`custom_container_enable_blackfire`
   * :ref:`custom_container_enable_elk_stack`
   * :ref:`custom_container_enable_mailhog`
   * :ref:`custom_container_enable_ngrok`
   * :ref:`custom_container_enable_python_flask`
   * :ref:`custom_container_enable_rabbitmq`
   * :ref:`custom_container_enable_solr`
   * :ref:`custom_container_enable_varnish`
