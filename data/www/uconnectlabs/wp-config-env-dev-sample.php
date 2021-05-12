<?php
define( 'WP_HOME', 'http://www.uconnectlabs.test' );
define( 'WP_SITEURL', WP_HOME );

define( 'DOMAIN_CURRENT_SITE', 'www.uconnectlabs.test' );
define( 'BLOG_ID_CURRENT_SITE', 2 );

define( 'DB_NAME', 'uconnectlabs' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', '' );
define( 'DB_HOST', '127.0.0.1' );

#define('DISABLE_ADMIN_REWRITE', true);

define( 'NOINDEX', true );
define( 'DONT_VERIFY_SCHOOL_EMAIL', true );

define( 'UC_OBJECT_CACHE', 'redis' );
define( 'UC_REDIS_CONFIG', [
	'host'     => 'redis',
	'port'     => 6379,
	'password' => 'redispass',
] );

define( 'WP_CACHE', false );

define( 'UC_GA_PROPERTY_ID', 'UA-19563948-7' );

define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', true );

//View emails at http://localhost:8025
define( 'UC_SMTP_HOST', 'mailhog' ); // Devilbox MailHog
define( 'UC_SMTP_PORT', 1025 ); // Devilbox MailHog
define( 'UC_SMTP_AUTH', false ); //

// SendGrid test.uconnectlabs.com API key
//define( 'UC_SMTP_PASS', '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' );

define( 'DYNCDN_DOMAINS', ' cdn.uconnectlabs.test' );
define( 'WP_CRON_LOCK_TIMEOUT', 60 );

define( 'UC_RECAPTCHA_VERIFY_HOST', false );


define( 'ENABLE_TRACING_CODE', true );
define( 'DEV_LOCAL_SERVER_USER', 'your_name' );
define( 'HIDE_PHP_ERRORS', true );
define( 'VERBOSE_PHP_ERRORS', true );

// Define some variables that are used when developing - DEV_LOCAL_SERVER_DOMAIN should
// be pointed to the office IP address, with ports 80 and 443 pointing to a dev laptop.
define( 'DEV_LOCAL_SERVER_DOMAIN', DEV_LOCAL_SERVER_USER . '.dev.loc.uconnectlabs.com' );

// Prevent the cron from running automated imports.
define( 'DEV_BRIDGE_ONLY_RUN_MANUAL_IMPORTS', false );

define( 'QM_DISABLE_ERROR_HANDLER', true );
