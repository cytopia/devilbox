.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _getting_started_create_your_first_project:

*************************
Create your first project
*************************

.. important::
   Ensure you have read :ref:`getting_started_directory_overview` to understand what is
   going on under the hood.

.. note::
   This section not only applies for one project, it applied for as many projects as you need.
   **There is no limit in the number of projects.**


**Table of Contents**

.. contents:: :local:


Step 1: visit Intranet vhost page
=================================

Before starting, have a look at the vhost page at http://localhost/vhosts.php or
http://127.0.0.1/vhosts.php

.. seealso:: :ref:`howto_find_docker_toolbox_ip_address`

It should look like the screenshot below and will actually already provide the information needed to create a new project.

.. include:: /_includes/figures/devilbox/devilbox-intranet-vhosts-empty.rst


Step 2: create a project directory
==================================

In your Devilbox git directory, navigate to ``./data/www`` and create a new directory.

.. note::
   Choose the directory name wisely, as it will be part of the domain for that project.
   For this example we will use ``project-1`` as our project name.

.. code-block:: bash

   # navigate to your Devilbox git directory
   host> cd path/to devilbox

   # navigate to the data directory
   host> cd data/www

   # create a new project directory named: project-1
   host> mkdir project-1

Visit the vhost page again and see what has changed: http://localhost/vhosts.php

.. include:: /_includes/figures/devilbox/devilbox-intranet-vhosts-missing-htdocs.rst

**So what has happened?**

By having created a project directory, the web server container has created a new virtual host. However it has noticed, that the actual document root directory does not yet exist and therefore it cannot serve any files yet.


Step 3: create a docroot directory
==================================

.. note::
   As desribed in :ref:`getting_started_directory_overview_docroot` the docroot directory name must be ``htdocs`` for now.

Navigate to your newly created project directory and create a directory named `htdocs` inside it.

.. code-block:: bash

   # navigate to your Devilbox git directory
   host> cd path/to devilbox

   # navigate to your above created project directory
   host> cd data/www/project-1

   # create the docroot directory
   host> mkdir htdocs

Vist the vhost page again and see what has changed: http://localhost/vhosts.php

.. include:: /_includes/figures/devilbox/devilbox-intranet-vhosts-missing-dns.rst

**So what has happened?**

By having created the docroot directory, the web server is now able to serve your files. However
it has noticed, that you have no way yet, to actually visit your project url, as no DNS record for
it exists yet.

The intranet already gives you the exact string that you can simply copy into your ``/etc/hosts``
(or ``C:\Windows\System32\drivers\etc`` for Windows) file on your host operating system to solve
this issue.


.. _getting_started_create_your_first_project_dns_entry:

Step 4: create a DNS entry
==========================

.. note::
   This step can also be automated via the bundled DNS server to automatically provide catch-all
   DNS entries to your host computer, but is outside the scope of this
   *getting started tutorial*.

When using native Docker, the Devilbox intranet will provide you the exact string you need to paste
into your ``/etc/hosts`` (or ``C:\Windows\System32\drivers\etc`` for Windows).

.. code-block:: bash

   # Open your /etc/hosts file with sudo or root privileges
   # and add the following DNS entry
   host> sudo vi /etc/hosts

   127.0.0.1 project-1.loc

.. seealso::

   * :ref:`howto_add_project_hosts_entry_on_mac`
   * :ref:`howto_add_project_hosts_entry_on_win`


Vist the vhost page again and see what has changed: http://localhost/vhosts.php

.. include:: /_includes/figures/devilbox/devilbox-intranet-vhosts-working.rst

**So what has happened?**

By having created the DNS record, the Devilbox intranet is aware that everything is setup now and
gives you a link to your new project.


Step 5: visit your project
==========================

On the intranet, click on your project link. This will open your project in a new Browser tab or
visit http://project-1.loc

.. include:: /_includes/figures/devilbox/devilbox-project-missing-index.rst

**So what has happened?**

Everything is setup now, however the webserver is trying to find a ``index.php`` file in your document root which does not yet exist.

So all is left for you to do is to add your HTML or PHP files.


Step 6: create a hello world file
=================================

Navigate to your docroot directory within your project and create a ``index.php`` file with some output.

.. code-block:: bash

   # navigate to your Devilbox git directory
   host> cd path/to devilbox

   # navigate to your projects docroot directory
   host> cd data/www/project-1/htdocs

   # Create a hello world index.php file
   host> echo "<?php echo 'hello world';" > index.php

Alternatively create an ``index.php`` file in ``data/www/project-1/htdocs`` with the following contents:

.. code-block:: php

   <?php echo 'hello world';

Visit your project url again and see what has changed: http://project-1.loc

.. include:: /_includes/figures/devilbox/devilbox-project-hello-world.rst


Checklist
=========

1. Project directory is created
2. Docroot directory is created
3. DNS entry is added to the host operating system
4. PHP files are added to your docroot directory

.. seealso:: :ref:`troubleshooting`


Further examples
================

If you already want to know how to setup specific frameworks on the Devilbox, jump directly to
their articles:

.. seealso::

   **Well tested frameworks on the Devilbox**

   * :ref:`example_setup_cakephp`
   * :ref:`example_setup_codeigniter`
   * :ref:`example_setup_craftcms`
   * :ref:`example_setup_drupal`
   * :ref:`example_setup_expressionengine`
   * :ref:`example_setup_joomla`
   * :ref:`example_setup_laravel`
   * :ref:`example_setup_magento`
   * :ref:`example_setup_phalcon`
   * :ref:`example_setup_photon_cms`
   * :ref:`example_setup_presta_shop`
   * :ref:`example_setup_shopware`
   * :ref:`example_setup_symfony`
   * :ref:`example_setup_typo3`
   * :ref:`example_setup_wordpress`
   * :ref:`example_setup_yii`
   * :ref:`example_setup_zend`

.. seealso::

   **Generic information for all unlisted frameworks**

   * :ref:`example_setup_other_frameworks`
