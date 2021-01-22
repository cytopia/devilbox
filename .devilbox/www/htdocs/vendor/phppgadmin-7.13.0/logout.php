<?php

/**
 * Logs a user out of the app
 *
 * $Id: logout.php,v 1.3 2003/09/10 01:55:52 chriskl Exp $
 */

if (!ini_get('session.auto_start')) {
	session_name('PPA_ID'); 
	session_start();
}
unset($_SESSION);
session_destroy();

header('Location: index.php');

?>
