<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<?php
if (!isset($_GET['name'])) {
	loadClass('Helper')->redirect('/vhosts.php');
}
if (!strlen($_GET['name'])) {
	loadClass('Helper')->redirect('/vhosts.php');
}
$vhost = $_GET['name'];
$found = false;
$vhosts = loadClass('Httpd')->getVirtualHosts();
foreach ($vhosts as $v) {
	if ($vhost == $v['name']) {
		$found = true;
		break;
	}
}
// Be safe before using outputs
$vhost = htmlentities($vhost);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">

			<h1>vhost-gen: <?php echo $vhost;?></h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php if (!$found): ?>
						<p>The Virtual Host "<?php echo $vhost; ?>" does not exist.</p>
					<?php else: ?>
						<?php $tpl = loadClass('Httpd')->getVhostgenTemplatePath($vhost); ?>
						<?php if (!$tpl): ?>
							<p>No custom vhost-gen configuration found for "<?php echo $vhost; ?>".</p>
						<?php else: ?>
							<p>Note: If the resulting virtual host config does not reflect the vhost-gen template changes, you will need to restart the Devilbox.</p>
							<a href="/vhosts.php"><i class="fa fa-chevron-left" aria-hidden="true"></i> Overview</a><br/>
							<br/><h3>virtual host config</h3><br/>
								<a title="Virtual host: <?php echo $vhost;?>.conf" target="_blank" href="/vhost.d/<?php echo $vhost;?>.conf">
									<i class="fa fa-external-link" aria-hidden="true"></i> <?php echo $vhost;?>.conf
								</a>
							<br/><br/><h3>vhost-gen config</h3><br/>
							<code><?php echo $tpl; ?></code><br/><br/>
							<?php $lines = file($tpl); ?>
<pre style="border: 1px solid black; padding:5px;"><code><?php foreach ($lines as $line): ?><?php echo $line; ?><?php endforeach; ?></code></pre>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>
