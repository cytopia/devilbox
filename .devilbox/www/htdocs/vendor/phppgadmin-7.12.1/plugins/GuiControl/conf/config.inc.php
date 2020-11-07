<?php

$plugin_conf = array(

    /**
     * Top Links
     **/
    'top_links' => array (
        // 'sql' => true,
        // 'history' => true,
        // 'find' => true,
        // 'logout' => true,
    ),

    /**
     * Tabs Links
     **/
     'tab_links' => array (
        'root' => array (
            // 'intro' => true,
            // 'servers' => true,
        ),
        'server' => array (
            // 'databases' => true,
            // 'roles' => true, /* postgresql 8.1+ */
            // 'users' => true, /* postgresql <8.1 */
            // 'groups' => true,
            // 'account' => true,
            // 'tablespaces' => true,
            // 'export' => true,
            // 'reports' => true,
        ),
        'database' => array (
            // 'schemas' => true,
            // 'sql' => true,
            // 'find' => true,
            // 'variables' => true,
            // 'processes' => true,
            // 'locks' => true,
            // 'admin' => true,
            // 'privileges' => true,
            // 'languages' => true,
            // 'casts' => true,
            // 'export' => true,
        ),
        'schema' => array(
            // 'tables' => true,
            // 'views' => true,
            // 'sequences' => true,
            // 'functions' => true,
            // 'fulltext' => true,
            // 'domains' => true,
            // 'aggregates' => true,
            // 'types' => true,
            // 'operators' => true,
            // 'opclasses' => true,
            // 'conversions' => true,
            // 'privileges' => true,
            // 'export' => true,
        ),
        'table' => array(
            // 'columns' => true,
            // 'indexes' => true,
            // 'constraints' => true,
            // 'triggers' => true,
            // 'rules' => true,
            // 'admin' => true,
            // 'info' => true,
            // 'privileges' => true,
            // 'import' => true,
            // 'export' => true,
        ),
        'view' => array (
            // 'columns' => true,
            // 'definition' => true,
            // 'rules' => true,
            // 'privileges' => true,
            // 'export' => true,
        ),
        'function' => array(
            // 'definition' => true,
            // 'privileges' => true,
        ),
        'aggregate' => array(
            // 'definition' => true,
        ),
        'role' => array(
            // 'definition' => true,
        ),
        'popup' => array(
            // 'sql' => true,
            // 'find' => true,
        ),
        'column' => array(
            // 'properties' => true,
            // 'privileges' => true,
        ),
        'fulltext' => array(
            // 'ftsconfigs' => true,
            // 'ftsdicts' => true,
            // 'ftsparsers' => true,
        ),
     ),

    /**
     * Trail Links
     **/
     // 'trail_links' => true, /* enable/disable the whole trail */

    /**
     * Navigation Links
     **/
     'navlinks' => array(
        'aggregates-properties' => array(
            // 'showall' => true,
            // 'alter' => true,
            // 'drop' => true,
        ),
        'aggregates-aggregates' => array(
            // 'create' => true,
        ),
        'all_db-databases' => array(
            // 'create' => true,
        ),
        'colproperties-colproperties' => array(
            // 'browse' => true,
            // 'alter' => true,
            // 'drop' => true,
        ),
        'constraints-constraints' => array(
            // 'addcheck' => true,
            // 'adduniq' => true,
            // 'addpk' => true,
            // 'addfk' => true,
        ),
        'display-browse' => array(
            // 'back' => true,
            // 'edit' => true,
            // 'collapse' => true,
            // 'createreport' => true,
            // 'createview' => true,
            // 'download' => true,
            // 'insert' => true,
            // 'refresh' => true,
        ),
        'domains-properties' => array(
            // 'drop' => true,
            // 'addcheck' => true,
            // 'alter' => true,
        ),
        'domains-domains' => array(
            // 'create' => true,
        ),
        'fulltext-fulltext' => array(
            // 'createconf' => true,
        ),
        'fulltext-viewdicts' => array(
            // 'createdict' => true,
        ),
        'fulltext-viewconfig' => array(
            // 'addmapping' => true,
        ),
        'functions-properties' => array(
            // 'showall' => true,
            // 'alter' => true,
            // 'drop' => true,
        ),
        'functions-functions' => array(
            // 'createpl' => true,
            // 'createinternal' => true,
            // 'createc' => true,
        ),
        'groups-groups' => array(
            // 'create' => true,
        ),
        'groups-properties' => array(
            // 'showall' => true,
        ),
        'history-history' => array(
            // 'refresh' => true,
            // 'download' => true,
            // 'clear' => true,
        ),
        'indexes-indexes' => array(
            // 'create' => true,
        ),
        'operators-properties' => array(
            // 'showall' => true,
        ),
        'privileges-privileges' => array(
            // 'grant' => true,
            // 'revoke' => true,
            // 'showalltables' => true,
            // 'showallcolumns' => true,
            // 'showallviews' => true,
            // 'showalldatabases' => true,
            // 'showallschemas' => true,
            // 'showallfunctions' => true,
            // 'showallsequences' => true,
            // 'showalltablespaces' => true,
        ),
        'reports-properties' => array(
            // 'showall' => true,
            // 'alter' => true,
            // 'exec' => true,
        ),
        'reports-reports' => array(
            // 'create' => true,
        ),
        'roles-account' => array(
            // 'changepassword' => true,
        ),
        'roles-properties' => array(
            // 'showall' => true,
            // 'alter' => true,
            // 'drop' => true,
        ),
        'roles-roles' => array(
            // 'create' => true,
        ),
        'rules-rules' => array(
            // 'create' => true,
        ),
        'schemas-schemas' => array(
            // 'create' => true,
        ),
        'sequences-properties' => array(
            // 'alter' => true,
            // 'nextval' => true,
            // 'restart' => true,
            // 'reset' => true,
        ),
        'sequences-sequences' => array(
            // 'create' => true,
        ),
        'servers-servers' => array(
            // 'showall' => true,
            /*we cannot filter the group names in navlinks presently*/
        ),
        'sql-form' => array(
            // 'back' => true,
            // 'alter' => true,
            // 'createreport' => true,
            // 'createview' => true,
            // 'download' => true,
        ),
        'tables-tables' => array(
            // 'create' => true,
            // 'createlike' => true,
        ),
        'tablespaces-tablespaces' => array(
            // 'create' => true,
        ),
        'tblproperties-tblproperties' => array(
            // 'browse' => true,
            // 'select' => true,
            // 'insert' => true,
	    'empty' => true,
            // 'drop' => true,
            // 'addcolumn' => true,
            // 'alter' => true,
        ),
        'triggers-triggers' => array(
            // 'create' => true,
        ),
        'types-properties' => array(
            // 'showall' => true,
        ),
        'types-types' => array(
            // 'create' => true,
            // 'createcomp' => true,
            // 'createenum' => true,
        ),
        'users-account' => array(
            // 'changepassword' => true,
        ),
        'users-users' => array(
            // 'create' => true,
        ),
        'viewproperties-definition' => array(
            // 'alter' => true,
        ),
        'viewproperties-viewproperties' => array(
            // 'browse' => true,
            // 'select' => true,
            // 'drop' => true,
            // 'alter' => true,
        ),
        'views-views' => array(
            // 'create' => true,
            // 'createwiz' => true,
        ),
     ),

     /**
      * action links
      **/

    'actionbuttons' => array(
        'admin-admin' => array(
            // 'edit' => true,
            // 'delete' => true,
        ),
        'aggregates-aggregates' => array(
            // 'alter' => true,
			// 'drop' => true,
        ),
        'all_db-databases' => array(
            // 'drop' => true,
            // 'privileges' => true,
            // 'alter' => true,
        ),
        'casts-casts' => array(
            // none
        ),
        'colproperties-colproperties' => array(
            // none
        ),
        'constraints-constraints' => array(
            // 'drop' => true,
        ),
        'conversions-conversions' => array(
            // none
        ),
        'database-variables' => array(
            // none
        ),
        'database-processes-preparedxacts' => array(
            // none
        ),
        'database-processes' => array(
            // 'cancel' => true,
            // 'kill' => true,
        ),
        'database-locks' => array(
            // none
        ),
        'display-browse' => array(
            // TODO
            // 'edit' => true,
            // 'delete' => true,
        ),
        'domains-properties' => array(
            // 'drop' => true,
        ),
        'domains-domains' => array(
            // 'alter' => true,
			// 'drop' => true,
        ),
        'fulltext-fulltext' => array(
            // 'drop' => true,
            // 'alter' => true,
        ),
        'fulltext-viewparsers' => array(
            // none
        ),
        'fulltext-viewdicts' => array(
            // 'drop' => true,
            // 'alter' => true,
        ),
        'fulltext-viewconfig' => array(
            // 'multiactions' => true,
            // 'drop' => true,
            // 'alter' => true,
        ),
        'functions-functions' => array(
            // 'multiactions' => true,
            // 'alter' => true,
            // 'drop' => true,
            // 'privileges' => true,
        ),
        'groups-members' => array(
            // 'drop' => true,
        ),
        'groups-properties' => array(
            // 'drop' => true,
        ),
        'history-history' => array(
            // 'run' => true,
            // 'remove' => true,
        ),
        'indexes-indexes' => array(
            // 'cluster' => true,
            // 'reindex' => true,
            // 'drop' => true,
        ),
        'info-referrers' => array(
            // 'properties' => true,
        ),
        'info-parents' => array(
            // 'properties' => true,
        ),
        'info-children' => array(
            // 'properties' => true,
        ),
        'languages-languages' => array(
            // none
        ),
        'opclasses-opclasses' => array(
            // none
        ),
        'operators-operators' => array(
             // 'drop' => true,
        ),
        'reports-reports' => array(
            // 'run' => true,
            // 'edit' => true,
            // 'drop' => true,
        ),
        'roles-roles' => array(
            // 'alter' => true,
            // 'drop' => true,
        ),
        'rules-rules' => array(
            // 'drop' => true,
        ),
        'schemas-schemas' => array(
            // 'multiactions' => true,
            // 'drop' => true,
            // 'privileges' => true,
            // 'alter' => true,
        ),
        'sequences-sequences' => array(
            // 'multiactions' => true,
            // 'drop' => true,
            // 'privileges' => true,
            // 'alter' => true,
        ),
        'servers-servers' => array(
            // 'logout' => true,
        ),
        'tables-tables' => array(
            // 'multiactions' => true,
            // 'browse' => true,
            // 'select' => true,
            // 'insert' => true,
            // 'empty' => true,
            // 'alter' => true,
            // 'drop' => true,
            // 'vacuum' => true,
            // 'analyze' => true,
            // 'reindex' => true,
        ),
        'tablespaces-tablespaces' => array(
            // 'drop' => true,
            // 'privileges' => true,
            // 'alter' => true,
        ),
        'tblproperties-tblproperties' => array(
            // 'browse' => true,
            // 'alter' => true,
            // 'privileges' => true,
            // 'drop' => true,
        ),
        'triggers-triggers' => array(
            // 'alter' => true,
            // 'drop' => true,
            // 'enable' => true,
            // 'disable' => true,
        ),
        'types-properties' => array(
            // none
        ),
        'types-types' => array(
            // 'drop' => true,
        ),
        'users-users' => array(
            // 'alter' => true,
            // 'drop' => true,
        ),
        'viewproperties-viewproperties' => array(
            // 'alter' => true,
        ),
        'views-views' => array(
            //'multiactions' => true,
            // 'browse' => true,
            // 'select' => true,
            //'alter' => true,
            //'drop' => true,
        ),
    ),
);

?>
