.. include:: /_includes/all.rst

.. _vhost_gen_virtual_host_templates:

**********************
Virtual host templates
**********************


**Table of Contents**

.. contents:: :local:


Overview
========

What is it?
-----------

vhost-gen templates are yaml files which contain a general definition for a virtual host definition.
Those templates contain placeholders in the form of ``__<NAME>__`` which will be replaced by
settings applied to the Devilbox.


.. seealso:: |ext_lnk_project_vhost_gen|


Template files
--------------

By default, vhost-gen templates are located within the Devilbox root directory under ``cfg/vhost-gen/``.
The templates file names are suffixed with ``-example`` and are absolutely identical to what is
shipped inside each Devilbox web server Docker container.

.. code-block:: bash

   host> tree -L 1 cfg/vhost-gen/

   cfg/vhost-gen/
   ├── apache22.yml-example
   ├── apache24.yml-example
   ├── nginx.yml-example
   └── README.md

   0 directories, 4 files

.. note::
   Also note that nginx stable and nginx mainline share the same template as their configuration
   syntax is identical.

Template sections
-----------------

All vhost-gen templates consist of three sections:

+----------------+----------------+
| Section        | Description    |
+================+================+
| ``vhost``      | |vhost|        |
+----------------+----------------+
| ``vhost_type`` | |vhost_type|   |
+----------------+----------------+
| ``features``   | |features|     |
+----------------+----------------+

.. |vhost| replace:: This is the part that is actually rendered into the vhost configuration. All other |br| sections will be inserted into this one.
.. |vhost_type| replace:: The vhost type determines the type of vhost: reverse proxy or document root based |br| vhost. The Devilbox currently does not support reverse proxy vhost.
.. |features| replace:: The feature section contains many sub-sections that are replaced into the ``vhost`` |br| section before final rendering.


Templates
=========

Apache 2.2 template
-------------------

.. literalinclude:: ../../cfg/vhost-gen/apache22.yml-example
   :caption: apache22.yml
   :language: yaml


Apache 2.4 template
-------------------

.. literalinclude:: ../../cfg/vhost-gen/apache24.yml-example
   :caption: apache24.yml
   :language: yaml


Nginx template
--------------

.. literalinclude:: ../../cfg/vhost-gen/nginx.yml-example
   :caption: nginx.yml
   :language: yaml

