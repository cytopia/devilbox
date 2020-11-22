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

			<h1>PHP custom configs</h1>
			<br/>
			<br/>
			<p>Shows your currently custom configuration files applied to the PHP-FPM container.</p>

			<div class="row">
				<div class="col-md-12">
					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th>Section</th>
								<th>Host</th>
								<th>Container</th>
								<th>Files</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Supervisord</th>
								<td>supervisor/</td>
								<td>/etc/supervisor/custom.d/</td>
								<td>
									<?php
										$files = glob('/etc/supervisor/custom.d/*.conf');
										if ($files) {
											foreach ($files as $file) {
												echo '<code>'.basename($file). '</code><br/>';
											}
										} else {
											echo 'No custom files';
										}
									 ?>
								</td>
							</tr>
							<tr>
								<th>Autostart (global)</th>
								<td>autostart/</td>
								<td>/startup.2.d/</td>
								<td>
									<?php
										$files = glob('/startup.2.d/*.sh');
										if ($files) {
											foreach ($files as $file) {
												echo '<code>'.basename($file). '</code><br/>';
											}
										} else {
											echo 'No custom files';
										}
									 ?>
								</td>
							</tr>
							<tr>
								<th>Autostart (version)</th>
								<td>cfg/php-startup-<?php echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;?>/</td>
								<td>/startup.1.d/</td>
								<td>
									<?php
										$files = glob('/startup.1.d/*.sh');
										if ($files) {
											foreach ($files as $file) {
												echo '<code>'.basename($file). '</code><br/>';
											}
										} else {
											echo 'No custom files';
										}
									 ?>
								</td>
							</tr>
							<tr>
								<th>PHP-FPM</th>
								<td>cfg/php-fpm-<?php echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;?>/</td>
								<td>/etc/php-fpm-custom.d/</td>
								<td>
									<?php
										$files = glob('/etc/php-fpm-custom.d/*.conf');
										if ($files) {
											foreach ($files as $file) {
												echo '<code>'.basename($file). '</code><br/>';
											}
										} else {
											echo 'No custom files';
										}
									 ?>
								</td>
							</tr>
							<tr>
								<th>PHP</th>
								<td>cfg/php-ini-<?php echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;?>/</td>
								<td>/etc/php-custom.d/</td>
								<td>
									<?php
										$files = glob('/etc/php-custom.d/*.ini');
										if ($files) {
											foreach ($files as $file) {
												echo '<code>'.basename($file). '</code><br/>';
											}
										} else {
											echo 'No custom files';
										}
									 ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>


		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>
