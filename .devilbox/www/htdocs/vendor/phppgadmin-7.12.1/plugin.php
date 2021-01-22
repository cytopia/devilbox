<?php
require_once('./libraries/lib.inc.php');

$plugin_manager->do_action($_REQUEST['plugin'], $_REQUEST['action']);
?>
