<?php require '../config.php'; ?>
<?php $Docker = loadClass('Docker'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navbar.php'; ?>

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
				<div class="col-md-3">
					<div class="circles">
						<div>
							<div class="bg-danger">
								<div>
									<div>
										<h3><?php echo $Docker->HTTPD_version();?></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ############################################################ -->
				<!-- PHP Docker Circle -->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<div class="circles">
						<div>
							<div class="bg-info">
								<div>
									<div>
										<h3><?php echo $Docker->PHP_version(); ?></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ############################################################ -->
				<!-- MySQL Docker Circle-->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<div class="circles">
						<div>
							<div class="bg-warning">
								<div>
									<div>
										<h3><?php echo $Docker->MySQL_version();?></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- ############################################################ -->
				<!-- PostgreSQL Docker Circle-->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<div class="circles">
						<div>
							<div class="bg-success">
								<div>
									<div>
										<h3><?php echo $Docker->Postgres_version();?></h3>
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
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">HTTPD docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP</th>
								<td><?php echo $HTTPD_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Hostname</th>
								<td><?php echo $HTTPD_HOST_NAME;?></td>
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
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">PHP docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP</th>
								<td><?php echo $PHP_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Hostname</th>
								<td><?php echo $PHP_HOST_NAME;?></td>
							</tr>							<tr>
								<th>Document Root</th>
								<td>/shared/httpd</td>
							</tr>
							<tr>
								<th>Custom config</th>
								<td>
									<?php foreach ($Docker->PHP_custom_config_files() as $file): ?>
										<?php echo $file.'<br/>';?>
									<?php endforeach; ?>
								</td>
							</tr>
							<tr>
								<?php $err=null; $valid = $Docker->PHP_has_valid_mysql_socket($err); ?>
								<th>MySQL socket</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo !$valid ? 'Error<br/><sub>'.$err.'</sub>' : $Docker->getEnv('MYSQL_SOCKET_PATH'); ?>
								</td>
							</tr>
							<tr>
								<?php $err=null; $valid = \devilbox\Mysql::testConnection($err, 'root', $Docker->getEnv('MYSQL_ROOT_PASSWORD'), 'localhost'); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> localhost:3306' : 'Failed: localhost:3306<br/><sub>'.$err.'</sub>'; ?>
								</td>
							</tr>
							<tr>
								<?php $err=null; $valid = \devilbox\Mysql::testConnection($err, 'root', $Docker->getEnv('MYSQL_ROOT_PASSWORD'), '127.0.0.1'); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> 127.0.0.1:3306' : 'Failed: 127.0.0.1:3306<br/><sub>'.$err.'</sub>'; ?>
								</td>
							</tr>
							<tr>
								<?php $err=null; $valid = \devilbox\Mysql::testConnection($err, 'root', $Docker->getEnv('MYSQL_ROOT_PASSWORD'), $MYSQL_HOST_ADDR); ?>
								<th>MySQL test</th>
								<td class="<?php echo !$valid ? 'bg-danger' : '';?>">
									<?php echo $valid ? '<span class="bg-success">OK</span> '.$MYSQL_HOST_ADDR.':3306' : 'Failed: '.$MYSQL_HOST_ADDR.':3306<br/><sub>'.$err.'</sub>'; ?>
								</td>
							</tr>

							<tr>
								<th>Postfix</th>
								<td><?php echo $Docker->getEnv('ENABLE_MAIL') ? '<span class="bg-success">OK</span> Enabled'  : '<span class="bg-danger">No</span> Disabled';?></td>
							</tr>


							<tr>
								<th>Xdebug enabled</th>
								<td>
									<?php $Xdebug = ($Docker->getEnv('PHP_XDEBUG_ENABLE') == 0) ? '' : $Docker->getEnv('PHP_XDEBUG_ENABLE'); ?>
									<?php if ($Xdebug == $Docker->PHP_config('xdebug.remote_enable')): ?>
										<?php echo $Docker->PHP_config('xdebug.remote_enable') == 1 ? 'Yes' : 'No'; ?>
									<?php else: ?>
										<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
										<?php echo 'Effective setting: '.$Docker->PHP_config('xdebug.remote_enable'); ?>
									<?php endif; ?>
								</td>
							</tr>
							<?php if ($Xdebug):?>
								<tr>
									<th>Xdebug remote</th>
									<td>
										<?php if ($Docker->getEnv('PHP_XDEBUG_REMOTE_HOST') == $Docker->PHP_config('xdebug.remote_host')): ?>
											<?php echo $Docker->PHP_config('xdebug.remote_host'); ?>
										<?php else: ?>
											<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
											<?php echo 'Effective setting: '.$Docker->PHP_config('xdebug.remote_host'); ?>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<th>Xdebug Port</th>
									<td>
										<?php if ($Docker->getEnv('PHP_XDEBUG_REMOTE_PORT') == $Docker->PHP_config('xdebug.remote_port')): ?>
											<?php echo $Docker->PHP_config('xdebug.remote_port'); ?>
										<?php else: ?>
											<?php echo '.env file setting differs from custom php .ini file<br/>'; ?>
											<?php echo 'Effective setting: '.$Docker->PHP_config('xdebug.remote_port'); ?>
										<?php endif; ?>
									</td>
								</tr>
							<?php endif; ?>




						</tbody>
					</table>
				</div>

				<!-- ############################################################ -->
				<!-- MySQL Docker -->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">MySQL docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP</th>
								<td><?php echo $MYSQL_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Hostname</th>
								<td><?php echo $MYSQL_HOST_NAME;?></td>
							</tr>
							<tr>
								<th>socket</th>
								<td><?php echo $Docker->MySQL_config('socket'); ?></td>
							</tr>
							<tr>
								<th>datadir</th>
								<td><?php echo $Docker->MySQL_config('datadir'); ?></td>
							</tr>
						</tbody>
					</table>
				</div>


				<!-- ############################################################ -->
				<!-- PostgreSQL Docker -->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th colspan="2">PostgreSQL docker</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>IP</th>
								<td><?php echo $POSTGRES_HOST_ADDR;?></td>
							</tr>
							<tr>
								<th>Hostname</th>
								<td><?php echo $POSTGRES_HOST_NAME;?></td>
							</tr>
							<tr>
								<th>socket</th>
								<td><?php
									$dir = $Docker->Postgres_config('unix_socket_directory');
									if (empty($dir)) {
										// Postgres 9.6
										$dir = $Docker->Postgres_config('unix_socket_directories');
									}
									echo $dir;

								?></td>
							</tr>
							<tr>
								<th>datadir</th>
								<td><?php echo $Docker->Postgres_config('data_directory'); ?></td>
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
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th>HTTPD docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Document Root</th>
								<td><?php echo $Docker->getEnv('HOST_PATH_TO_WWW_DOCROOTS');?></td>
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
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th>PHP docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Document Root</th>
								<td><?php echo $Docker->getEnv('HOST_PATH_TO_WWW_DOCROOTS');?></td>
							</tr>
							<tr>
								<th>Custom config</th>
								<td>./etc</td>
							</tr>
							<tr>
								<th>MySQL socket</th>
								<td>./run/mysql/mysqld.sock</td>
							</tr>							<tr>
								<th>Log directory</th>
								<td>./log</td>
							</tr>
						</tbody>
					</table>
				</div>


				<!-- ############################################################ -->
				<!-- MySQL Docker Mounts -->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th>MySQL docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>MySQL datadir</th>
								<td><?php echo $Docker->getEnv('HOST_PATH_TO_MYSQL_DATADIR');?></td>
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


				<!-- ############################################################ -->
				<!-- PostgreSQL Docker Mounts -->
				<!-- ############################################################ -->
				<div class="col-md-3">
					<table class="table table-striped table-sm font-small">
						<thead class="thead-inverse">
							<tr>
								<th>Postgres docker</th>
								<th>host</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>Postgres datadir</th>
								<td><?php echo $Docker->getEnv('HOST_PATH_TO_POSTGRES_DATADIR');?></td>
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
