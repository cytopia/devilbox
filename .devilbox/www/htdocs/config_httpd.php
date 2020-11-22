<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">

			<h1>Httpd custom configs</h1>
			<br/>
			<br/>
			<p>Shows your currently custom configuration files applied to the Httpd container.</p>

			<div class="row">
				<div class="col-md-12">
					<?php $vHosts = loadClass('Httpd')->getVirtualHosts(); ?>
					<?php $custom = false; ?>
					<?php foreach ($vHosts as $vHost): ?>
						<?php if (($vhostGen = loadClass('Httpd')->getVhostgenTemplatePath($vHost['name'])) !== false): ?>
							<?php $custom = true; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<?php if ($custom): ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Project</th>
									<th>Host</th>
									<th>Container</th>
									<th>Files</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach ($vHosts as $vHost): ?>
								<?php if (($vhostGen = loadClass('Httpd')->getVhostgenTemplatePath($vHost['name'])) !== false): ?>
									<tr>
										<th><?php echo $vHost['domain']; ?></th>
										<td><?php echo loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR').'/'.$vHost['name'].'/'.loadClass('Helper')->getEnv('HTTPD_TEMPLATE_DIR');?></td>
										<td><?php echo dirname($vhostGen); ?></td>
										<td><code><?php echo basename($vhostGen); ?></code></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<p>No custom configurations applied.</p>
					<?php endif; ?>
				</div>
			</div>


		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>
