.. _connect_to_other_docker_container:

*********************************
Connect to other Docker container
*********************************

Other Docker container can either be accessed by connecting back to the host os or by adding its
image directly to the Devilbox stack.


**Table of Contents**

.. contents:: :local:


Any Docker container on host os
===============================

1. To connect to any other Docker container on your host os from within the Devilbox Docker
   container, you first need to make sure, you are able to connect to your host os from within the
   Devilbox Docker container.

   .. seealso:: :ref:`connect_to_host_os`

2. Once you are able to connect to the host os, start any other Docker container and make its
   port that you want to access available to your host os by specifying ``-p``.
   An example with e.g. an external `Grafana <https://hub.docker.com/r/grafana/grafana/>`_ container
   might look like this:

   .. code-block:: bash

      host> docker run -d --name=grafana -p 3000:3000 grafana/grafana

   You can then connect to your host os on port ``3000`` from within the Devilbox Docker container
   and be able to use it.


Add Docker container to Devilbox stack
======================================

Alternatively you can also add any Docker container to the Devilbox network by adding an image
it to the Devilbox stack directly.

.. seealso:: :ref:`add_your_own_docker_image`
