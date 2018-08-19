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

+---------------------------+-------+----------+----------------+
| Container                 | Name  | Hostname | IP Address     |
+===========================+=======+==========+================+
| DNS                       | bind  | bind     | 172.16.238.100 |
+---------------------------+-------+----------+----------------+
| PHP                       | php   | php      | 172.16.238.10  |
+---------------------------+-------+----------+----------------+
| Apache, Nginx             | httpd | httpd    | 172.16.238.11  |
+---------------------------+-------+----------+----------------+
| MySQL, MariaDB, PerconaDB | mysql | mysql    | 172.16.238.12  |
+---------------------------+-------+----------+----------------+
| PostgreSQL                | pgsql | pgsql    | 172.16.238.13  |
+---------------------------+-------+----------+----------------+
| Redis                     | redis | redis    | 172.16.238.14  |
+---------------------------+-------+----------+----------------+
| Memcached                 | memcd | memcd    | 172.16.238.15  |
+---------------------------+-------+----------+----------------+
| MongoDB                   | mongo | mongo    | 172.16.238.16  |
+---------------------------+-------+----------+----------------+


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
