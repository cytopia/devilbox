.. :hidden:

.. include:: /_includes/all.rst

**********************
Devilbox documentation
**********************

|img_banner|

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

   read-first
   features
   devilbox-purpose


.. toctree::
   :caption: Getting started
   :maxdepth: 2
   :numbered:

   getting-started/prerequisites
   getting-started/install-the-devilbox
   getting-started/start-the-devilbox
   getting-started/devilbox-intranet
   getting-started/directory-overview
   getting-started/create-your-first-project
   getting-started/enter-the-php-container
   getting-started/change-container-versions


.. toctree::
   :caption: Intermediate
   :maxdepth: 2
   :numbered:

   intermediate/setup-auto-dns
   intermediate/setup-valid-https
   intermediate/configure-php-xdebug
   intermediate/enable-disable-php-modules
   intermediate/read-log-files
   intermediate/email-catch-all
   intermediate/add-custom-environment-variables
   intermediate/work-inside-the-php-container
   intermediate/source-code-analysis
   intermediate/best-practice


.. toctree::
   :caption: Advanced
   :maxdepth: 2
   :numbered:

   advanced/customize-php-globally
   advanced/customize-webserver-globally
   advanced/connect-to-host-os
   advanced/connect-to-other-docker-container
   advanced/connect-to-external-hosts
   advanced/add-custom-cname-records
   advanced/add-your-own-docker-image
   advanced/overwrite-existing-docker-image


.. toctree::
   :caption: vhost-gen
   :maxdepth: 2

   vhost-gen/virtual-host-templates
   vhost-gen/customize-all-virtual-hosts-globally
   vhost-gen/customize-specific-virtual-host
   vhost-gen/example-add-subdomains

.. toctree::
   :caption: Enable custom container
   :maxdepth: 2

   custom-container/enable-all-container
   custom-container/enable-blackfire
   custom-container/enable-mailhog
   custom-container/enable-rabbitmq
   custom-container/enable-solr


.. toctree::
   :caption: Corporate Usage
   :maxdepth: 2

   corporate-usage/shared-devilbox-server-in-lan
   corporate-usage/use-external-databases
..
   corporate-usage/deploy-devilbox-via-ansible
   corporate-usage/access-colleagues-devilbox
   corporate-usage/access-devilbox-from-android
   corporate-usage/access-devilbox-from-iphone


.. toctree::
   :caption: Maintenance
   :maxdepth: 2

   maintenance/checkout-different-devilbox-release
   maintenance/remove-stopped-container
   maintenance/update-the-devilbox
   maintenance/remove-the-devilbox
   maintenance/backup-and-restore-mysql
   maintenance/backup-and-restore-pgsql
   maintenance/backup-and-restore-mongo


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
   :maxdepth: 2
   :caption: Examples

   examples/setup-cakephp
   examples/setup-codeigniter
   examples/setup-craftcms
   examples/setup-drupal
   examples/setup-joomla
   examples/setup-laravel
   examples/setup-magento
   examples/setup-phalcon
   examples/setup-photon-cms
   examples/setup-presta-shop
   examples/setup-shopware
   examples/setup-symfony
   examples/setup-typo3
   examples/setup-wordpress
   examples/setup-yii
   examples/setup-zend
   examples/setup-other-frameworks


.. toctree::
   :caption: Readings
   :maxdepth: 2

   readings/syncronize-container-permissions
   readings/available-container
   readings/available-tools


.. toctree::
   :caption: Support
   :maxdepth: 1

   support/artwork
   support/blogs-videos-and-use-cases
   support/troubleshooting
   support/faq
   support/howto
