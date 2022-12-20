The Devilbox ships various example configurations to overwrite the default stack. Those files are
located under ``compose/`` in the Devilbox git directory.

``docker-compose.override.yml-all`` has all examples combined in one file for easy copy/paste.
However, each example also exists in its standalone file as shown below:

.. code-block:: bash

   host> tree -L 1 compose/
   compose/
   ├── docker-compose.override.yml-all
   ├── docker-compose.override.yml-blackfire
   ├── docker-compose.override.yml-elk
   ├── docker-compose.override.yml-mailhog
   ├── docker-compose.override.yml-meilisearch
   ├── docker-compose.override.yml-ngrok
   ├── docker-compose.override.yml-php-community
   ├── docker-compose.override.yml-python-flask
   ├── docker-compose.override.yml-rabbitmq
   ├── docker-compose.override.yml-solr
   ├── docker-compose.override.yml-varnish
   └── README.md

   0 directories, 10 files

.. seealso:: :ref:`custom_container_enable_all_additional_container`
