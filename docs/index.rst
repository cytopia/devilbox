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


.. raw:: html

   <img src="https://raw.githubusercontent.com/devilbox/artwork/master/submissions_diagrams/cytopia/01/png/architecture-full.png" />

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
   getting-started/important


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
   :caption: Autostart commands
   :maxdepth: 2

   autostart/custom-scripts-per-php-version
   autostart/custom-scripts-globally
   autostart/autostarting-nodejs-apps


.. toctree::
   :caption: vhost-gen
   :maxdepth: 2

   vhost-gen/virtual-host-templates
   vhost-gen/customize-all-virtual-hosts-globally
   vhost-gen/customize-specific-virtual-host
   vhost-gen/virtual-host-vs-reverse-proxy
   vhost-gen/example-add-subdomains


.. toctree::
   :caption: reverse-proxy
   :maxdepth: 2

   reverse-proxy/reverse-proxy-with-https
   reverse-proxy/reverse-proxy-for-custom-docker


.. toctree::
   :caption: Enable custom container
   :maxdepth: 2

   custom-container/enable-all-container
   custom-container/enable-php-community
   custom-container/enable-blackfire
   custom-container/enable-elk-stack
   custom-container/enable-mailhog
   custom-container/enable-meilisearch
   custom-container/enable-ngrok
   custom-container/enable-python-flask
   custom-container/enable-rabbitmq
   custom-container/enable-solr
   custom-container/enable-varnish


.. toctree::
   :caption: Corporate Usage
   :maxdepth: 2

   corporate-usage/shared-devilbox-server-in-lan
   corporate-usage/use-external-databases
   corporate-usage/showcase-over-the-internet
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
   examples/setup-codeigniter4
   examples/setup-contao
   examples/setup-craftcms
   examples/setup-drupal
   examples/setup-expressionengine
   examples/setup-joomla
   examples/setup-laravel
   examples/setup-magento2
   examples/setup-phalcon
   examples/setup-photon-cms
   examples/setup-presta-shop
   examples/setup-processwire
   examples/setup-shopware
   examples/setup-symfony
   examples/setup-typo3
   examples/setup-wordpress
   examples/setup-yii
   examples/setup-zend
   examples/setup-other-frameworks

.. toctree::
   :maxdepth: 2
   :caption: Examples - reverse proxy

   examples/setup-reverse-proxy-nodejs
   examples/setup-reverse-proxy-sphinx-docs
   examples/setup-reverse-proxy-python-flask


.. toctree::
   :caption: Readings
   :maxdepth: 2

   readings/syncronize-container-permissions
   readings/available-container
   readings/available-tools


.. toctree::
   :caption: Support
   :maxdepth: 1

   Devilbox Forums <https://devilbox.discourse.group/>
   support/troubleshooting
   support/faq
   support/howto
   support/blogs-videos-and-use-cases
   support/artwork

.. toctree::
   :caption: 3rd party projects
   :maxdepth: 1

   third-party/devilbox-cli
   third-party/nginx-acme
