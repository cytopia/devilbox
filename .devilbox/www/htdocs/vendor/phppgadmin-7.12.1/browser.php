<?php

	/**
	 * Main object browser.  This page first shows a list of databases and then
	 * if you click on a database it shows a list of database objects in that
	 * database.
	 *
	 * $Id: browser.php,v 1.59 2008/02/13 23:03:05 ioguix Exp $
	 */

	// Include application functions
	$_no_db_connection = true;
	$_no_bottom_link = true;
	include_once('./libraries/lib.inc.php');
	
	// Output header
	$misc->printHeader('', '
		<script src="xloadtree/xtree2.js" type="text/javascript"></script>
		<script src="xloadtree/xloadtree2.js" type="text/javascript"></script>
		<style type="text/css">
			.webfx-tree-children { background-image: url("' . $misc->icon('I') . '"); }
		</style>');
	
	$misc->printBody('browser');
	echo "<div dir=\"ltr\">\n";
?>

	<div class="logo">
		<a href="intro.php" target="detail">
			<?php echo htmlspecialchars($appName) ?> 
		</a>
	</div>
	<div class="refreshTree"><a href="browser.php" target="browser" ><img src="<?php echo $misc->icon('Refresh'); ?>" alt="<?php echo $lang['strrefresh']; ?>" title="<?php echo $lang['strrefresh']; ?>" /></a></div>
<script type="text/javascript">

webFXTreeConfig.rootIcon		= "<?php echo $misc->icon('Servers') ?>";
webFXTreeConfig.openRootIcon	= "<?php echo $misc->icon('Servers') ?>";
webFXTreeConfig.folderIcon		= "";
webFXTreeConfig.openFolderIcon	= "";
webFXTreeConfig.fileIcon		= "";
webFXTreeConfig.iIcon			= "<?php echo $misc->icon('I') ?>";
webFXTreeConfig.lIcon			= "<?php echo $misc->icon('L') ?>";
webFXTreeConfig.lMinusIcon		= "<?php echo $misc->icon('Lminus') ?>";
webFXTreeConfig.lPlusIcon		= "<?php echo $misc->icon('Lplus') ?>";
webFXTreeConfig.tIcon			= "<?php echo $misc->icon('T') ?>";
webFXTreeConfig.tMinusIcon		= "<?php echo $misc->icon('Tminus') ?>";
webFXTreeConfig.tPlusIcon		= "<?php echo $misc->icon('Tplus') ?>";
webFXTreeConfig.blankIcon		= "<?php echo $misc->icon('blank') ?>";
webFXTreeConfig.loadingIcon		= "<?php echo $misc->icon('Loading') ?>";
webFXTreeConfig.loadingText		= "<?php echo $lang['strloading'] ?>";
webFXTreeConfig.errorIcon		= "<?php echo $misc->icon('ObjectNotFound') ?>";
webFXTreeConfig.errorLoadingText = "<?php echo $lang['strerrorloading'] ?>";
webFXTreeConfig.reloadText		= "<?php echo $lang['strclicktoreload'] ?>";

// Set default target frame:
WebFXTreeAbstractNode.prototype.target = 'detail';

// Disable double click:
WebFXTreeAbstractNode.prototype._ondblclick = function(){}

// Show tree XML on double click - for debugging purposes only
/*
// UNCOMMENT THIS FOR DEBUGGING (SHOWS THE SOURCE XML)
WebFXTreeAbstractNode.prototype._ondblclick = function(e){
	var el = e.target || e.srcElement;

	if (this.src != null)
		window.open(this.src, this.target || "_self");
	return false;
};
*/
var tree = new WebFXLoadTree("<?php echo $lang['strservers']; ?>", "servers.php?action=tree", "servers.php");

tree.write();
tree.setExpanded(true);

</script>

<?php
   // Output footer
   echo "</div>\n";
   $misc->printFooter();

?>
