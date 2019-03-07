:orphan:

.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _howto_ssh_into_docker_toolbox:

***********************
SSH into Docker Toolbox
***********************

**Table of Contents**

.. contents:: :local:


Requirements
============

You shell must have an SSH client (the ``ssh`` command or equivalent).

.. seealso::
   * :ref:`howto_open_terminal_on_mac`
   * :ref:`howto_open_terminal_on_win`
   * :ref:`howto_find_docker_toolbox_ip_address`


Manual
======

Before going to use the automated approach, you should understand how to fetch all required
information via the ``docker-machine`` command.


Gather all information
----------------------

1. Get active Toolbox machine name

   .. code-block:: bash

      host> docker-machine active
      default

2. Print all information

   .. code-block:: bash

     host> docker-machine -D ssh default
     Host : localhost
     Port : 51701
     User : docker
     Key : .docker\machine\machines\default\id_rsa


Gather specific information
---------------------------

1. Get active Toolbox machine name

   .. code-block:: bash

      host> docker-machine active
      default

2. Get SSH username (Using machine name ``default`` from above)

   .. code-block:: bash

      host> docker-machine inspect default --format={{.Driver.SSHUser}}
      docker

3. Get SSH public key (Using machine name ``default`` from above)

   .. code-block:: bash

      host> docker-machine inspect default --format={{.Driver.SSHKeyPath}}
      .docker\machine\machines\default\id_rsa

4. Get local SSH port (Using machine name ``default`` from above)

   .. code-block:: bash

      host> docker-machine inspect default --format={{.Driver.SSHPort}}
      51701

5. Get Docker Toolbox IP address (Using machine name ``default`` from above)

   .. code-block:: bash

      host> docker-machine ip default
      192.168.99.100


SSH into Docker Toolbox
-----------------------

Now with the above gathered information you can ssh into Docker Toolbox in two different ways:

1. via local port-forwarded ssh port (automatically forwarded by Docker Toolbox)

   .. code-block:: bash

      host> ssh -i .docker\machine\machines\default\id_rsa -p 51701 docker@127.0.0.1

2. via Docker Toolbox IP address

   .. code-block:: bash

      host> ssh -i .docker\machine\machines\default\id_rsa docker@192.168.99.100


Automated
=========

Instead of typing all of the above manually each time, you can also create a small bash script
to automate this.

1. Create a file ``ssh-docker.sh`` and add the following to it:

   .. code-block:: bash
      :caption: ssh-docker.sh

      #!/bin/bash
      docker_machine_name=$(docker-machine active)
      docker_ssh_user=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHUser}})
      docker_ssh_key=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHKeyPath}})
      docker_ssh_port=$(docker-machine inspect $docker_machine_name --format={{.Driver.SSHPort}})

      ssh -i $docker_ssh_key -p $docker_ssh_port $docker_ssh_user@localhost

2. Run it:

   .. code-block:: bash

      host> bash ssh-docker.sh

.. seealso:: |ext_lnk_stackoverflow_ssh_into_docker_machine|
