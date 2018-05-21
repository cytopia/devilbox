**********************
devilbox documentation
**********************
.. :hidden:

.. image:: img/banner.png

The Devilbox is a modern dockerized LAMP and MEAN stack for local development on Linux, MacOS
and Windows.

It allows you to have an unlimited number of projects ready without having to install
any external software and without having to configure any virtual hosts. As well as providing a
very flexible development stack that you can run offline. (Internet is only required to initially
pull docker container).

The only thing you will have to do is to create a new directory on the filesystem and your virtual
host is ready to be served with your custom domain.


.. important::
   :ref:`read_first`
     Ensure you have read this document to understand how this documentation works.


.. toctree::
   :maxdepth: 2
   :caption: About

   about/read-first
   about/features

.. toctree::
   :maxdepth: 2
   :numbered:
   :caption: Getting started

   getting-started/install-the-devilbox
   getting-started/update-the-devilbox
   getting-started/start-the-devilbox
   getting-started/directory-overview
   getting-started/create-your-first-project
   getting-started/read-log-files
   getting-started/email-catch-all
   getting-started/enter-the-php-container
   getting-started/the-intranet
   getting-started/best-practice


.. toctree::
   :caption: Maintenance
   :maxdepth: 2

   maintenance/install
   maintenance/update
   maintenance/uninstall
   maintenance/start-the-devilbox
   maintenance/add-service-to-running-devilbox
   maintenance/remove-service-from-running-devilbox
   maintenance/backup-and-restore-mysql
   maintenance/backup-and-restore-pgsql
   maintenance/backup-and-restore-mongo


.. toctree::
   :maxdepth: 2
   :caption: Tutorials

   tutorials/communicating-with-external-hosts
   tutorials/add-your-own-docker-image
   tutorials/overwrite-existing-docker-image
   tutorials/adding-subdomains
   tutorials/configure-database-in-your-project
   tutorials/change-document-root
   tutorials/change-container-versions
   tutorials/work-inside-the-container
   tutorials/enable-xdebug
   tutorials/customize-vhost
   tutorials/custom-apache-modules
   tutorials/custom-environment-variables
   tutorials/password-protect-intranet
   tutorials/disable-intranet
   tutorials/static-code-analysis


.. toctree::
   :maxdepth: 2
   :caption: Examples

   examples/setup-cakephp
   examples/setup-codeigniter
   examples/setup-drupal
   examples/setup-joomla
   examples/setup-laravel
   examples/setup-phalcon
   examples/setup-photon-cms
   examples/setup-symfony
   examples/setup-wordpress
   examples/setup-yii
   examples/setup-zend
   examples/setup-other-frameworks


.. toctree::
   :caption: Project configuration
   :maxdepth: 2

   configuration-project/dns-records
   configuration-project/domain
   configuration-project/custom-vhost


.. toctree::
   :caption: Global configuration
   :maxdepth: 2

   configuration-global/https-ssl
   configuration-global/auto-dns


.. toctree::
   :caption: Configuration files
   :maxdepth: 2

   configuration-files/env-file
   configuration-files/docker-compose-yml
   configuration-files/docker-compose-override-yml
   configuration-files/apache-conf
   configuration-files/nginx-conf
   configuration-files/php-ini
   configuration-files/php-fpm-conf
   configuration-files/my-cnf
   configuration-files/bashrc-sh


.. toctree::
   :caption: Readings
   :maxdepth: 2

   installation/docker-installation
   installation/docker-toolbox
   readings/available-container
   readings/available-tools
   readings/remove-stopped-container
   readings/syncronize-container-permissions


.. toctree::
   :caption: Advanced
   :maxdepth: 2

   advanced/technical
   advanced/hacking


.. toctree::
   :caption: Support
   :maxdepth: 2

   support/faq
   support/troubleshooting
   support/contributing
   support/blogs-videos-and-use-cases
   support/artwork
