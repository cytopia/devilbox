.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _available_container:

*******************
Available container
*******************

.. note::
   :ref:`start_the_devilbox`
       Find out how to start some or all container.

The following tables give you an overview about all container that can be started.
When doing a selective start, use the ``Name`` value to specify the container to start up.

Core container
==============

These container are well integrated into the Devilbox intranet and are considered core container:

.. include:: /_includes/snippets/core-container.rst


Additional container
====================

Additional container that are not yet integrated into the Devilbox intranet are also available.
Those container come via ``docker-compose.override.yml`` and must explicitly be enabled.
They are disabled by default to prevent accidentally starting too many container and making your
computer unresponsive.

.. include:: /_includes/snippets/additional-container.rst

.. seealso::
   * :ref:`custom_container_enable_all_additional_container`
   * :ref:`custom_container_enable_blackfire`
   * :ref:`custom_container_enable_mailhog`
   * :ref:`custom_container_enable_rabbitmq`
   * :ref:`custom_container_enable_solr`
