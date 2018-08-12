<?php

class StdLog
{
	public function write($type, $msg, $namespace = null) {
		if (App::instance()->config['log']['threshold'] < Log::instance()->$type) {
			return;
		}

        $ip        = isset($_SERVER['REMOTE_ADDR']) ? "[{$_SERVER['REMOTE_ADDR']}]" : '[Unknown Address]';
        $namespace = isset($namespace) ? '['.ucwords(strtolower($namespace)).']' : '';

		if (in_array($type, [Log::ERROR, Log::NOTICE, Log::WARNING])) {
			$stream = fopen('php://stderr', 'w');
		} else {
			$stream = fopen('php://stdout', 'w');
		}

		fwrite($stream, "PHPREDMIN: {$ip} {$namespace} [{$type}]: {$msg}\n");
		fclose($stream);
	}
}
