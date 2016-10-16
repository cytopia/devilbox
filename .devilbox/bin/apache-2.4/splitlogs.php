#!/usr/bin/env php
<?php

// TODO: logpath could also be passed via CMD argument
// so this script could be more general
$path = '/var/log/apache-2.4';
$fh_timeout = 30; // 30 sek.

$fd = fopen('php://stdin', 'r');

while (!feof($fd)) {

	$row = fgets($fd);

	list($vhost, $h, $l, $u, $t, $r, $s, $b, $referrer, $ua) = explode(';', $row, 10);

	if (!isset(${$vhost})) {
		${$vhost} = fopen($path . '/' . $vhost . '_access.log', 'a+');
	}
	$lastwrite[$vhost] = time();
	fputs(${$vhost}, "$h $l $u $t $r $s $b $referrer $ua");

	foreach ($lastwrite as $vhost => $time) {
		if ((time() - ($time + 30)) >= 0) {
			fclose(${$vhost});
			unset(${$vhost});
			unset($lastwrite[$vhost]);
		}
	}
}
