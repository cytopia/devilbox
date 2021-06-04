.. include:: /_includes/all.rst
.. include:: /_includes/snippets/__ANNOUNCEMENTS__.rst

.. _available_tools:

***************
Available tools
***************

Each PHP container version brings the same tools, so you can safely switch versions without having
to worry to have less or more tools available.

.. seealso:: :ref:`work_inside_the_php_container`

The PHP container is your workhorse and these are your tools:

+----------------------+---------------------------------------+
| Binary               | Tool                                  |
+======================+=======================================+
| ``ansible``          | |ext_lnk_tool_ansible|                |
+----------------------+---------------------------------------+
| various binaries     | |ext_lnk_tool_awesome_ci|             |
+----------------------+---------------------------------------+
| ``brew``             | |ext_lnk_tool_homebrew|               |
+----------------------+---------------------------------------+
| ``codecept``         | |ext_lnk_tool_codecept|               |
+----------------------+---------------------------------------+
| ``composer``         | |ext_lnk_tool_composer|               |
+----------------------+---------------------------------------+
| ``dep``              | |ext_lnk_tool_dep|                    |
+----------------------+---------------------------------------+
| ``drush``            | |ext_lnk_tool_drush|                  |
+----------------------+---------------------------------------+
| ``drupal``           | |ext_lnk_tool_drupal_console|         |
+----------------------+---------------------------------------+
| ``eslint``           | |ext_lnk_tool_eslint|                 |
+----------------------+---------------------------------------+
| ``git``              | |ext_lnk_tool_git|                    |
+----------------------+---------------------------------------+
| ``git-flow``         | |ext_lnk_tool_git_flow|               |
+----------------------+---------------------------------------+
| ``gulp``             | |ext_lnk_tool_gulp|                   |
+----------------------+---------------------------------------+
| ``grunt``            | |ext_lnk_tool_grunt|                  |
+----------------------+---------------------------------------+
| ``jsonlint``         | |ext_lnk_tool_jsonlint|               |
+----------------------+---------------------------------------+
| ``laravel``          | |ext_lnk_tool_laravel|                |
+----------------------+---------------------------------------+
| ``linkcheck``        | |ext_lnk_tool_linkcheck|              |
+----------------------+---------------------------------------+
| ``lumen``            | |ext_lnk_tool_lumen|                  |
+----------------------+---------------------------------------+
| ``mdl``              | |ext_lnk_tool_mdl|                    |
+----------------------+---------------------------------------+
| ``mdlint``           | |ext_lnk_tool_mdlint|                 |
+----------------------+---------------------------------------+
| ``mongo*``           | Various MongoDB client tools          |
+----------------------+---------------------------------------+
| ``mysql*``           | Various MySQL client tools            |
+----------------------+---------------------------------------+
| ``mysqldump-secure`` | |ext_lnk_tool_mysqldump_secure|       |
+----------------------+---------------------------------------+
| ``ng``               | |ext_lnk_tool_angular_cli|            |
+----------------------+---------------------------------------+
| ``node``             | |ext_lnk_tool_node|                   |
+----------------------+---------------------------------------+
| ``npm``              | |ext_lnk_tool_npm|                    |
+----------------------+---------------------------------------+
| ``pg*``              | Various PostgreSQL client tools       |
+----------------------+---------------------------------------+
| ``phalcon``          | |ext_lnk_tool_phalcon|                |
+----------------------+---------------------------------------+
| ``php-cs-fixer``     | |ext_lnk_tool_php_cs_fixer|           |
+----------------------+---------------------------------------+
| ``phpcs``            | |ext_lnk_tool_phpcs|                  |
+----------------------+---------------------------------------+
| ``phpcbf``           | |ext_lnk_tool_phpcbf|                 |
+----------------------+---------------------------------------+
| ``phpmd``            | |ext_lnk_tool_phpmd|                  |
+----------------------+---------------------------------------+
| ``phpunit``          | |ext_lnk_tool_phpunit|                |
+----------------------+---------------------------------------+
| ``photon``           | |ext_lnk_tool_photon|                 |
+----------------------+---------------------------------------+
| ``pm2``              | |ext_lnk_tool_pm2|                    |
+----------------------+---------------------------------------+
| ``redis*``           | Various Redis client tools            |
+----------------------+---------------------------------------+
| ``sass``             | |ext_lnk_tool_sass|                   |
+----------------------+---------------------------------------+
| ``scss-lint``        | |ext_lnk_tool_scss_lint|              |
+----------------------+---------------------------------------+
| ``ssh``              | |ext_lnk_tool_ssh|                    |
+----------------------+---------------------------------------+
| ``symfony``          | |ext_lnk_tool_symfony|                |
+----------------------+---------------------------------------+
| ``tig``              | |ext_lnk_tool_tig|                    |
+----------------------+---------------------------------------+
| ``vue``              | |ext_lnk_tool_vue|                    |
+----------------------+---------------------------------------+
| ``webpack``          | |ext_lnk_tool_webpack|                |
+----------------------+---------------------------------------+
| ``wp``               | |ext_lnk_tool_wp|                     |
+----------------------+---------------------------------------+
| ``yamllint``         | |ext_lnk_tool_yamllint|               |
+----------------------+---------------------------------------+
| ``yarn``             | |ext_lnk_tool_yarn|                   |
+----------------------+---------------------------------------+
| ``yq``               | |ext_lnk_tool_yq|                     |
+----------------------+---------------------------------------+

.. note::
   If you are in need of other tools, open up an issue at |ext_lnk_tool_github_issues|
   and ask for it, this can usually be implemented very quickly.

.. seealso::
   If you ever feel those tools are out-dated, simply update your Docker images.
   Docker images are built every night to ensure latest tools and security patches:
   :ref:`update_the_devilbox_update_the_docker_images`
