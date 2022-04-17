<?php

declare(strict_types=1);

return [
    'services' => [
        'advisor' => [
            'class' => PhpMyAdmin\Advisor::class,
            'arguments' => [
                '$dbi' => '@dbi',
                '$expression' => '@expression_language',
            ],
        ],
        'browse_foreigners' => [
            'class' => PhpMyAdmin\BrowseForeigners::class,
            'arguments' => ['@template'],
        ],
        'config' => [
            'class' => PhpMyAdmin\Config::class,
            'arguments' => [CONFIG_FILE],
        ],
        'central_columns' => [
            'class' => PhpMyAdmin\Database\CentralColumns::class,
            'arguments' => ['@dbi'],
        ],
        'check_user_privileges' => [
            'class' => PhpMyAdmin\CheckUserPrivileges::class,
            'arguments' => ['@dbi'],
        ],
        'create_add_field' => [
            'class' => PhpMyAdmin\CreateAddField::class,
            'arguments' => ['@dbi'],
        ],
        'designer' => [
            'class' => PhpMyAdmin\Database\Designer::class,
            'arguments' => [
                '$dbi' => '@dbi',
                '$relation' => '@relation',
                '$template' => '@template',
            ],
        ],
        'designer_common' => [
            'class' => PhpMyAdmin\Database\Designer\Common::class,
            'arguments' => [
                '$dbi' => '@dbi',
                '$relation' => '@relation',
            ],
        ],
        'error_handler' => [
            'class' => PhpMyAdmin\ErrorHandler::class,
        ],
        'error_report' => [
            'class' => PhpMyAdmin\ErrorReport::class,
            'arguments' => [
                '@http_request',
                '@relation',
                '@template',
            ],
        ],
        'events' => [
            'class' => PhpMyAdmin\Database\Events::class,
            'arguments' => [
                '@dbi',
                '@template',
                '@response',
            ],
        ],
        'export' => [
            'class' => PhpMyAdmin\Export::class,
            'arguments' => ['@dbi'],
        ],
        'export_options' => [
            'class' => PhpMyAdmin\Export\Options::class,
            'arguments' => [
                '@relation',
                '@export_template_model',
            ],
        ],
        'export_template_model' => [
            'class' => PhpMyAdmin\Export\TemplateModel::class,
            'arguments' => ['@dbi'],
        ],
        'expression_language' => [
            'class' => Symfony\Component\ExpressionLanguage\ExpressionLanguage::class,
        ],
        'http_request' => [
            'class' => PhpMyAdmin\Utils\HttpRequest::class,
        ],
        'import' => [
            'class' => PhpMyAdmin\Import::class,
        ],
        'insert_edit' => [
            'class' => PhpMyAdmin\InsertEdit::class,
            'arguments' => ['@dbi'],
        ],
        'navigation' => [
            'class' => PhpMyAdmin\Navigation\Navigation::class,
            'arguments' => [
                '@template',
                '@relation',
                '@dbi',
            ],
        ],
        'normalization' => [
            'class' => PhpMyAdmin\Normalization::class,
            'arguments' => [
                '$dbi' => '@dbi',
                '$relation' => '@relation',
                '$transformations' => '@transformations',
                '$template' => '@template',
            ],
        ],
        'operations' => [
            'class' => PhpMyAdmin\Operations::class,
            'arguments' => [
                '$dbi' => '@dbi',
                '$relation' => '@relation',
            ],
        ],
        'relation' => [
            'class' => PhpMyAdmin\Relation::class,
            'arguments' => [
                '@dbi',
                '@template',
            ],
        ],
        'relation_cleanup' => [
            'class' => PhpMyAdmin\RelationCleanup::class,
            'arguments' => [
                '@dbi',
                '@relation',
            ],
        ],
        'replication' => [
            'class' => PhpMyAdmin\Replication::class,
        ],
        'replication_gui' => [
            'class' => PhpMyAdmin\ReplicationGui::class,
            'arguments' => [
                '$replication' => '@replication',
                '$template' => '@template',
            ],
        ],
        'response' => [
            'class' => PhpMyAdmin\Response::class,
            'factory' => [PhpMyAdmin\Response::class, 'getInstance'],
        ],
        'server_plugins' => [
            'class' => PhpMyAdmin\Server\Plugins::class,
            'arguments' => ['@dbi'],
        ],
        'server_privileges' => [
            'class' => PhpMyAdmin\Server\Privileges::class,
            'arguments' => [
                '@template',
                '@dbi',
                '@relation',
                '@relation_cleanup',
            ],
        ],
        'sql' => [
            'class' => PhpMyAdmin\Sql::class,
            'arguments' => [
                '@dbi',
                '@relation',
                '@relation_cleanup',
                '@operations',
                '@transformations',
                '@template',
            ],
        ],
        'sql_query_form' => [
            'class' => PhpMyAdmin\SqlQueryForm::class,
            'arguments' => ['$template' => '@template'],
        ],
        'status_data' => [
            'class' => PhpMyAdmin\Server\Status\Data::class,
        ],
        'status_monitor' => [
            'class' => PhpMyAdmin\Server\Status\Monitor::class,
            'arguments' => ['@dbi'],
        ],
        'table_maintenance' => [
            'class' => PhpMyAdmin\Table\Maintenance::class,
            'arguments' => ['$dbi' => '@dbi'],
        ],
        'table_partition' => [
            'class' => PhpMyAdmin\Table\Partition::class,
            'arguments' => ['$dbi' => '@dbi'],
        ],
        'table_search' => [
            'class' => PhpMyAdmin\Table\Search::class,
            'arguments' => ['$dbi' => '@dbi'],
        ],
        'template' => [
            'class' => PhpMyAdmin\Template::class,
        ],
        'tracking' => [
            'class' => PhpMyAdmin\Tracking::class,
            'arguments' => [
                '$sqlQueryForm' => '@sql_query_form',
                '$template' => '@template',
                '$relation' => '@relation',
            ],
        ],
        'transformations' => [
            'class' => PhpMyAdmin\Transformations::class,
        ],
        'user_password' => [
            'class' => PhpMyAdmin\UserPassword::class,
            'arguments' => ['@server_privileges'],
        ],
        'user_preferences' => [
            'class' => PhpMyAdmin\UserPreferences::class,
        ],
        PhpMyAdmin\Response::class => 'response',
        PhpMyAdmin\DatabaseInterface::class => 'dbi',
    ],
];
