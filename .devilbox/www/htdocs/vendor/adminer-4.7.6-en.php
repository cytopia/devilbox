<?php
function adminer_object()
{
	// Required to run any plugin.
	include_once "./adminer/plugins/plugin.php";

	// Plugins auto-loader.
	foreach (glob("./adminer/plugins/*.php") as $filename) {
		include_once "./$filename";
	}

	// Specify enabled plugins here.
	$plugins = [
		new AdminerDatabaseHide(['information_schema', 'mysql', 'performance_schema', 'sys']),
		new AdminerCollations(),
		new AdminerJsonPreview(),

		new AdminerEditForeign(),
		new AdminerEnumOption(),
		new AdminerDisplayForeignKeyName(),
		new AdminerTablesFilter(),
		new AdminerDumpBz2(),
		new AdminerReadableDates(),

		// Color variant can by specified in constructor parameter.
		new AdminerTheme("default-blue"),
	];

	class AdminerCustomization extends AdminerPlugin {
		function permanentLogin($create = false) {
			// key used for permanent login
			return '45oQ%5RS0NeKEuj2Rr90UeizA88S5syP4d9192w7A3*PCNSu3B';
		}
	}

	return new AdminerCustomization($plugins);
}

// Include original Adminer or Adminer Editor.
include "./adminer/adminer.php";
