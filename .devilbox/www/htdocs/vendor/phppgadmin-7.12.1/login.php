<?php

	/**
	 * Login screen
	 *
	 * $Id: login.php,v 1.38 2007/09/04 19:39:48 ioguix Exp $
	 */
	global $conf, $plugin_manager;
	
	// This needs to be an include once to prevent lib.inc.php infinite recursive includes.
	// Check to see if the configuration file exists, if not, explain
	require_once('./libraries/lib.inc.php');

	if (!isset($plugin_manager))
		$plugin_manager = new PluginManager($_SESSION['webdbLanguage']);

	$misc->printHeader($lang['strlogin']);
	$misc->printBody();
	$misc->printTrail('root');
	
	$server_info = $misc->getServerInfo($_REQUEST['server']);
	
	$misc->printTitle(sprintf($lang['strlogintitle'], $server_info['desc']));
	
	if (isset($msg)) $misc->printMsg($msg);

	$md5_server = md5($_REQUEST['server']);
?>

<form id="login_form" action="redirect.php" method="post" name="login_form">
<?php
	if (!empty($_POST)) $vars =& $_POST;
	else $vars =& $_GET;
	// Pass request vars through form (is this a security risk???)
	foreach ($vars as $key => $val) {
		if (substr($key,0,5) == 'login') continue;
		echo "<input type=\"hidden\" name=\"", htmlspecialchars($key), "\" value=\"", htmlspecialchars($val), "\" />\n";
	}
?>
	<input type="hidden" name="loginServer" value="<?php echo htmlspecialchars($_REQUEST['server']); ?>" />
	<table class="navbar" border="0" cellpadding="5" cellspacing="3">
		<tr>
			<td><?php echo $lang['strusername']; ?></td>
			<td><input type="text" name="loginUsername" value="<?php if (isset($_POST['loginUsername'])) echo htmlspecialchars($_POST['loginUsername']); ?>" size="24" /></td>
		</tr>
		<tr>
			<td><?php echo $lang['strpassword']; ?></td>
			<td><input id="loginPassword" type="password" name="loginPassword_<?php echo $md5_server; ?>" size="24" /></td>
		</tr>
	</table>
<?php if (sizeof($conf['servers']) > 1) : ?>
	<p><input type="checkbox" id="loginShared" name="loginShared" <?php echo isset($_POST['loginShared']) ? 'checked="checked"' : '' ?> /><label for="loginShared"><?php echo $lang['strtrycred'] ?></label></p>
<?php endif; ?>
	<p><input type="submit" name="loginSubmit" value="<?php echo $lang['strlogin']; ?>" /></p>
</form>

<script type="text/javascript">
	var uname = document.login_form.loginUsername;
	var pword = document.login_form.loginPassword_<?php echo $md5_server; ?>;
	if (uname.value == "") {
		uname.focus();
	} else {
		pword.focus();
	}
</script>

<?php
	// Output footer
	$misc->printFooter();
?>
