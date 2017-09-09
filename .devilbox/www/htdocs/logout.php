<?php require '../config.php'; ?>
<?php

if (isset($_GET['id'])) {
	if ($_GET['id'] == session_id()) {
		loadClass('Helper')->logout();
		loadClass('Helper')->redirect('/login.php');
	}
}

loadClass('Helper')->redirect('/');
