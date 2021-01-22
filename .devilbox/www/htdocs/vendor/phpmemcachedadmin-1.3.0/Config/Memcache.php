<?php
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
return array (
	'stats_api' => 'Server',
	'slabs_api' => 'Server',
	'items_api' => 'Server',
	'get_api' => 'Server',
	'set_api' => 'Server',
	'delete_api' => 'Server',
	'flush_all_api' => 'Server',
	'connection_timeout' => 1,
	'max_item_dump'=> 100,
	'refresh_rate' => 2,
	'memory_alert' => 80,
	'hit_rate_alert' => 90,
	'eviction_alert' => 0,
	'file_path' => '/tmp',
	'servers' => array (
		'Devilbox Memcached' => array (
			'memcd' =>  array (
				'hostname' => 'memcd',
				'port'	 => '11211',
			),
		),
	),
);
