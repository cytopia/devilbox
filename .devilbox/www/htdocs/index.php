<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navigation.php'; ?>

		<div class="container">

			<div class="row">
				<div class="col-md-12">
					<img class="float-xs-left" src="/assets/img/devilbox_80.png" />
					<h1 class="float-xs-left text-muted">The devilbox</h1>
				</div>
			</div>
			<br/>
			<hr/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<h2 class="text-xs-center">Docker setup</h2>
				</div>
			</div>

			<br/>
			<br/>

			<div class="row">
				<!-- ############################################################ -->
				<!-- HTTPD Docker Circle -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<div class="circles">
						<div>
							<div class="bg-danger">
								<div>
									<div>
										<?php echo getHttpVersion();?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ############################################################ -->
				<!-- PHP Docker Circle -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<div class="circles">
						<div>
							<div class="bg-info">
								<div>
									<div>
										<?php echo getPHPVersion(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ############################################################ -->
				<!-- MySQL Docker Circle-->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<div class="circles">
						<div>
							<div class="bg-warning">
								<div>
									<div>
										<?php echo getMySQLVersion();?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br/><br/>


			<div class="row">
				<!-- ############################################################ -->
				<!-- HTTPD Docker -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">httpd docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP Address</th>
								<td><?php echo $HTTPD_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Document Root</th>
								<td>/shared/httpd</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- ############################################################ -->
				<!-- PHP Docker -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">php docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP Address</th>
								<td><?php echo $PHP_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Document Root</th>
								<td>/shared/httpd</td>
							</tr>
							<tr>
								<th>Custom config</th>
								<td>
									<?php foreach (scandir('/etc/php-custom.d') as $file): ?>
										<?php if (preg_match('/.*\.ini$/', $file) === 1): ?>
											<?php echo $file.'<br/>';?>
										<?php endif; ?>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr>
								<?php $error; $valid = php_has_valid_mysql_socket($error); ?>
								<th>MySQL socket</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo !$valid ? 'Error' : $ENV['MYSQL_SOCKET_PATH']; ?>
								</td>
							</tr>
							<tr>
								<?php $err; $valid = my_mysql_connection_test($err, 'localhost'); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> localhost:3306' : 'Failoed: localhost:3306'; ?>
								</td>
							</tr>
							<tr>
								<?php $err; $valid = my_mysql_connection_test($err, '127.0.0.1'); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> 127.0.0.1:3306' : 'Failed: 127.0.0.1:3306'; ?>
								</td>
							</tr>
							<tr>
								<?php $err; $valid = my_mysql_connection_test($err, $MYSQL_HOST_ADDR); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> '.$MYSQL_HOST_ADDR.':3306' : 'Failed: '.$MYSQL_HOST_ADDR.':3306'; ?>
								</td>
							</tr>



							<tr>
								<th>Xdebug enabled</th>
								<td>
									<?php if ($ENV['PHP_XDEBUG_ENABLE'] == ini_get('xdebug.remote_enable')): ?>
										<?php echo ini_get('xdebug.remote_enable') == 1 ? 'Yes' : ini_get('xdebug.remote_enable'); ?>
									<?php else: ?>
										<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
										<?php echo 'Effective setting: '.ini_get('xdebug.remote_enable'); ?>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th>Xdebug remote</th>
								<td>
									<?php if ($ENV['PHP_XDEBUG_REMOTE_HOST'] == ini_get('xdebug.remote_host')): ?>
										<?php echo ini_get('xdebug.remote_host'); ?>
									<?php else: ?>
										<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
										<?php echo 'Effective setting: '.ini_get('xdebug.remote_host'); ?>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th>Xdebug Port</th>
								<td>
									<?php if ($ENV['PHP_XDEBUG_REMOTE_PORT'] == ini_get('xdebug.remote_port')): ?>
										<?php echo ini_get('xdebug.remote_port'); ?>
									<?php else: ?>
										<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
										<?php echo 'Effective setting: '.ini_get('xdebug.remote_port'); ?>
									<?php endif; ?>
								</td>
							</tr>




						</tbody>
					</table>
				</div>

				<!-- ############################################################ -->
				<!-- MySQL Docker -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">db docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP Address</th>
								<td><?php echo $MYSQL_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>MySQL socket</th>
								<td><?php echo getMySQLConfigByKey('socket'); ?></td>
							</tr>
							<tr>
								<th>MySQL datadir</th>
								<td><?php echo getMySQLConfigByKey('datadir'); ?></td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>




			<br/>
			<br/>
			<div class="row">
				<div class="col-md-12">
					<h2 class="text-xs-center">Docker to Host mounts</h2>
				</div>
			</div>
			<br/>
			<br/>




			<div class="row">
				<!-- ############################################################ -->
				<!-- HTTPD Docker Mounts -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th>httpd docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Document Root</th>
								<td><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?></td>
							</tr>
							<tr>
								<th>Log directory</th>
								<td>./log</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- ############################################################ -->
				<!-- PHP Docker Mounts -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th>php docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Document Root</th>
								<td><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?></td>
							</tr>
							<tr>
								<th>Custom config</th>
								<td>./etc</td>
							</tr>
							<tr>
								<th>Log directory</th>
								<td>./log</td>
							</tr>
						</tbody>
					</table>
				</div>


				<!-- ############################################################ -->
				<!-- MySQL Docker Mounts -->
				<!-- ############################################################ -->
				<div class="col-md-4">
					<table class="table table-striped table-sm">
						<thead class="thead-inverse">
							<tr>
								<th>db docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>MySQL datadir</th>
								<td><?php echo $ENV['HOST_PATH_TO_MYSQL_DATADIR'];?></td>
							</tr>
							<tr>
								<th>MySQL socket</th>
								<td>./run/mysql/mysqld.sock</td>
							</tr>
							<tr>
								<th>Log directory</th>
								<td>./log</td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here
		})();
		</script>
	</body>
</html>
