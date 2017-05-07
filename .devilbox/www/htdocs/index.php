<?php require '../config.php'; ?>
<?php $Docker = loadClass('Docker'); ?>
<?php

/*********************************************************************************
 *
 * I N I T I A L I Z A T I O N
 *
 *********************************************************************************/

/*************************************************************
 * Load required files
 *************************************************************/
loadFile('Php');
loadFile('Httpd');
loadFile('Mysql');
loadFile('Pgsql');
loadFile('Redis');
loadFile('Memcd');


/*************************************************************
 * Get availability
 *************************************************************/
$avail_php		= \devilbox\Php::isAvailable($GLOBALS['PHP_HOST_NAME']);
$avail_httpd	= \devilbox\Httpd::isAvailable($GLOBALS['HTTPD_HOST_NAME']);
$avail_mysql	= \devilbox\Mysql::isAvailable($GLOBALS['MYSQL_HOST_NAME']);
$avail_pgsql	= \devilbox\Pgsql::isAvailable($GLOBALS['PGSQL_HOST_NAME']);
$avail_redis	= \devilbox\Redis::isAvailable($GLOBALS['REDIS_HOST_NAME']);
$avail_memcd	= \devilbox\Memcd::isAvailable($GLOBALS['MEMCD_HOST_NAME']);



/*************************************************************
 * Test Connectivity
 *************************************************************/

$connection = array();
$error	= null;

// ---- HTTPD ----
$host	= $GLOBALS['HTTPD_HOST_NAME'];
$succ	= \devilbox\Httpd::testConnection($error, $host);
$connection['Httpd'][$host] = array(
	'error' => $error,
	'host' => $host,
	'succ' => $succ
);
$host	= \devilbox\Httpd::getIpAddress($GLOBALS['HTTPD_HOST_NAME']);
$succ	= \devilbox\Httpd::testConnection($error, $host);
$connection['Httpd'][$host] = array(
	'error' => $error,
	'host' => $host,
	'succ' => $succ
);
$host	= 'random.'.loadClass('Php')->getTldSuffix();
$succ	= \devilbox\Httpd::testConnection($error, $host);
$connection['Httpd'][$host] = array(
	'error' => $error,
	'host' => $host,
	'succ' => $succ
);

// ---- MYSQL ----
if ($avail_mysql) {
	$host	= $GLOBALS['MYSQL_HOST_NAME'];
	$succ	= \devilbox\Mysql::testConnection($error, $host, 'root', loadClass('Docker')->getEnv('MYSQL_ROOT_PASSWORD'));
	$connection['MySQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= \devilbox\Mysql::getIpAddress($GLOBALS['MYSQL_HOST_NAME']);
	$succ	= \devilbox\Mysql::testConnection($error, $host, 'root', loadClass('Docker')->getEnv('MYSQL_ROOT_PASSWORD'));
	$connection['MySQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= '127.0.0.1';
	$succ	= \devilbox\Mysql::testConnection($error, $host, 'root', loadClass('Docker')->getEnv('MYSQL_ROOT_PASSWORD'));
	$connection['MySQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
}

// ---- PGSQL ----
if ($avail_pgsql) {
	$host	= $GLOBALS['PGSQL_HOST_NAME'];
	$succ	= \devilbox\Pgsql::testConnection($error, $host, loadClass('Docker')->getEnv('PGSQL_ROOT_USER'), loadClass('Docker')->getEnv('PGSQL_ROOT_PASSWORD'));
	$connection['PgSQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= \devilbox\Pgsql::getIpAddress($GLOBALS['PGSQL_HOST_NAME']);
	$succ	= \devilbox\Pgsql::testConnection($error, $host, loadClass('Docker')->getEnv('PGSQL_ROOT_USER'), loadClass('Docker')->getEnv('PGSQL_ROOT_PASSWORD'));
	$connection['PgSQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= '127.0.0.1';
	$succ	= \devilbox\Pgsql::testConnection($error, $host, loadClass('Docker')->getEnv('PGSQL_ROOT_USER'), loadClass('Docker')->getEnv('PGSQL_ROOT_PASSWORD'));
	$connection['PgSQL'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
}

// ---- REDIS ----
if ($avail_redis) {
	$host	= $GLOBALS['REDIS_HOST_NAME'];
	$succ	= \devilbox\Redis::testConnection($error, $host);
	$connection['Redis'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= \devilbox\Redis::getIpAddress($GLOBALS['REDIS_HOST_NAME']);
	$succ	= \devilbox\Redis::testConnection($error, $host);
	$connection['Redis'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= '127.0.0.1';
	$succ	= \devilbox\Redis::testConnection($error, $host);
	$connection['Redis'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
}

// ---- MEMCACHED ----
if ($avail_memcd) {
	$host	= $GLOBALS['MEMCD_HOST_NAME'];
	$succ	= \devilbox\Memcd::testConnection($error, $host);
	$connection['Memcached'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= \devilbox\Memcd::getIpAddress($GLOBALS['MEMCD_HOST_NAME']);
	$succ	= \devilbox\Memcd::testConnection($error, $host);
	$connection['Memcached'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
	$host	= '127.0.0.1';
	$succ	= \devilbox\Memcd::testConnection($error, $host);
	$connection['Memcached'][$host] = array(
		'error' => $error,
		'host' => $host,
		'succ' => $succ
	);
}


/*************************************************************
 * Test Health
 *************************************************************/
$HEALTH_TOTAL = 0;
$HEALTH_FAILS = 0;

foreach ($connection as $docker) {
	foreach ($docker as $conn) {
		if (!$conn['succ']) {
			$HEALTH_FAILS++;
		}
		$HEALTH_TOTAL++;
	}
}
$HEALTH_PERCENT = 100 - ceil(100 * $HEALTH_FAILS / $HEALTH_TOTAL);










/*********************************************************************************
 *
 * F U N C T I O N S
 *
 *********************************************************************************/

function getCirle($name) {
	switch ($name) {
		case 'php':
			$class = 'bg-info';
			$version = loadClass('Php')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Php')->getName();
			break;
		case 'httpd':
			$class = 'bg-info';
			$version = loadClass('Httpd')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Httpd')->getName();
			break;
		case 'httpd':
			$class = 'bg-info';
			$version = loadClass('Httpd')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Httpd')->getName();
			break;
		case 'mysql':
			$class = 'bg-warning';
			$version = loadClass('Mysql')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Mysql')->getName();
			break;
		case 'pgsql':
			$class = 'bg-warning';
			$version = loadClass('Pgsql')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Pgsql')->getName();
			break;
		case 'redis':
			$class = 'bg-danger';
			$version = loadClass('Redis')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Redis')->getName();
			break;
		case 'memcd':
			$class = 'bg-danger';
			$version = loadClass('Memcd')->getVersion();
			$available = $GLOBALS['avail_'.$name];
			$name = loadClass('Memcd')->getName();
			break;
		default:
			$available = false;
			$version = '';
			break;
	}

	$style = 'color:black;';
	$version = '('.$version.')';
	if (!$available) {
		$class = '';
		$style = 'background-color:gray;';
		$version = '&nbsp;';
	}
	$circle = '<div class="circles">'.
				'<div>'.
					'<div class="'.$class.'" style="'.$style.'">'.
						'<div>'.
							'<div><br/><strong>'.$name.'</strong><br/><small style="color:#333333">'.$version.'</small></div>'.
						'</div>'.
					'</div>'.
				'</div>'.
			'</div>';
	return $circle;
}




/*********************************************************************************
 *
 * H T M L
 *
 *********************************************************************************/

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php $FONT_AWESOME = TRUE; require '../include/head.php'; ?>
	</head>

	<body style="background: #1f1f1f;">
		<?php require '../include/navbar.php'; ?>


		<div class="container">


			<!-- ############################################################ -->
			<!-- Version/Health -->
			<!-- ############################################################ -->
			<div class="row">

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-hashtag"></i> Version</div>
						<div class="dash-box-body">
							<strong>Devilbox</strong> <?php echo $GLOBALS['DEVILBOX_VERSION']; ?> <small>(<?php echo $GLOBALS['DEVILBOX_DATE']; ?>)</small>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<img src="/assets/img/devilbox_80.png" style="width:100%;" />
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-bug" aria-hidden="true"></i> Health</div>
						<div class="dash-box-body">
							<div class="meter">
							  <span style="color:black; width: <?php echo $HEALTH_PERCENT; ?>%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $HEALTH_PERCENT; ?>%</span>
							</div>
						</div>
					</div>
				</div>

			</div><!-- /row -->



			<!-- ############################################################ -->
			<!-- DASH -->
			<!-- ############################################################ -->
			<div class="row">

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-cog" aria-hidden="true"></i> Base Stack</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('php'); ?>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('httpd'); ?>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('dns'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-database" aria-hidden="true"></i> SQL Stack</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('mysql'); ?>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('pgsql'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 col-sm-4 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-file-o" aria-hidden="true"></i> NoSQL Stack</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('redis'); ?>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('memcd'); ?>
								</div>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-4" style="margin-bottom:15px;">
									<?php echo getCirle('mongodb'); ?>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div><!-- /row -->


			<!-- ############################################################ -->
			<!-- Settings / Status -->
			<!-- ############################################################ -->

			<div class="row">
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-info-circle" aria-hidden="true"></i> PHP Container Setup</div>
						<div class="dash-box-body">
							<table class="table table-striped table-hover table-bordered table-sm font-small">
								<thead class="thead-inverse">
									<tr>
										<th colspan="2">Settings</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>uid</th>
										<td><?php echo loadClass('Php')->getUid(); ?></td>
									</tr>
									<tr>
										<th>gid</th>
										<td><?php echo loadClass('Php')->getGid(); ?></td>
									</tr>
									<tr>
										<th>vHost TLD</th>
										<td>*.<?php echo loadClass('Php')->getTldSuffix(); ?></td>
									</tr>
									<tr>
										<th>DNS</th>
										<td>Enabled</td>
									</tr>
									<tr>
										<th>Postfix</th>
										<td>Enabled</td>
									</tr>
									<tr>
										<th>Xdebug</th>
										<td>Enabled</td>
									</tr>
									<tr>
										<th>Xdebug Remote</th>
										<td>192.168.0.215</td>
									</tr>
									<tr>
										<th>Xdebug Port</th>
										<td>9000</td>
									</tr>
								</tbody>
							</table>

							<table class="table table-striped table-hover table-bordered table-sm font-small">
								<thead class="thead-inverse">
									<tr>
										<th colspan="2">Tools</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>composer</th>
										<td><?php if (($version = loadClass('Php')->getComposerVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
									<tr>
										<th>drush</th>
										<td><?php if (($version = loadClass('Php')->getDrushVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
									<tr>
										<th>drush-console</th>
										<td><?php if (($version = loadClass('Php')->getDrushConsoleVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
									<tr>
										<th>git</th>
										<td><?php if (($version = loadClass('Php')->getGitVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
									<tr>
										<th>node</th>
										<td><?php if (($version = loadClass('Php')->getNodeVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
									<tr>
										<th>npm</th>
										<td><?php if (($version = loadClass('Php')->getNpmVersion()) === false) {echo '<span class="text-danger">not installed</span>';}else{echo $version;}; ?></td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 offset-lg-4 offset-md-0 offset-sm-0 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-info-circle" aria-hidden="true"></i> PHP Container Status</div>
						<div class="dash-box-body">
							<p><small>The PHP Docker can connect to the following services via the specified hostnames and IP addresses.</small></p>
							<table class="table table-striped table-hover table-bordered table-sm font-small">
								<thead class="thead-inverse">
									<tr>
										<th>Service</th>
										<th>Hostname / IP</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($connection as $name => $docker): ?>
										<tr>
											<th rowspan="3" class="align-middle"><?php echo $name; ?> connect</th>
											<?php $i=1; foreach ($docker as $conn): ?>

											<?php if ($conn['succ']): ?>
												<?php $text = '<span class="text-success"><i class="fa fa-check-square"></i></span> '.$conn['host']; ?>
											<?php else: ?>
												<?php $text = '<span class="text-danger"><i class="fa fa-exclamation-triangle"></i></span> '.$conn['host'].'<br/>'.$conn['error']; ?>
											<?php endif; ?>

												<?php if ($i == 1): $i++;?>
													<td>
														<?php echo $text; ?>
													</td>
													</tr>
												<?php else: $i++;?>
													<tr>
														<td>
															<?php echo $text; ?>
														</td>
													</tr>
												<?php endif; ?>
											<?php endforeach; ?>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>


			</div><!-- /row -->


			<!-- ############################################################ -->
			<!-- TABLES -->
			<!-- ############################################################ -->
			<div class="row">

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-share-alt" aria-hidden="true"></i> Networking</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small">
										<thead class="thead-inverse">
											<tr>
												<th>Docker</th>
												<th>Hostname</th>
												<th>IP</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>php</th>
												<td><?php echo $GLOBALS['PHP_HOST_NAME']; ?></td>
												<td><?php echo \devilbox\Php::getIpAddress($GLOBALS['PHP_HOST_NAME']); ?></td>
											</tr>
											<tr>
												<th>httpd</th>
												<td><?php echo $GLOBALS['HTTPD_HOST_NAME']; ?></td>
												<td><?php echo \devilbox\Httpd::getIpAddress($GLOBALS['HTTPD_HOST_NAME']); ?></td>
											</tr>
											<?php if ($avail_mysql): ?>
												<tr>
													<th>mysql</th>
													<td><?php echo $GLOBALS['MYSQL_HOST_NAME']; ?></td>
													<td><?php echo \devilbox\Mysql::getIpAddress($GLOBALS['MYSQL_HOST_NAME']); ?></td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_pgsql): ?>
												<tr>
													<th>pgsql</th>
													<td><?php echo $GLOBALS['PGSQL_HOST_NAME']; ?></td>
													<td><?php echo \devilbox\Pgsql::getIpAddress($GLOBALS['PGSQL_HOST_NAME']); ?></td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_redis): ?>
												<tr>
													<th>redis</th>
													<td><?php echo $GLOBALS['REDIS_HOST_NAME']; ?></td>
													<td><?php echo \devilbox\Redis::getIpAddress($GLOBALS['REDIS_HOST_NAME']); ?></td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_memcd): ?>
												<tr>
													<th>memcached</th>
													<td><?php echo $GLOBALS['MEMCD_HOST_NAME']; ?></td>
													<td><?php echo \devilbox\Memcd::getIpAddress($GLOBALS['MEMCD_HOST_NAME']); ?></td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-wrench" aria-hidden="true"></i> Ports</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small">
										<thead class="thead-inverse">
											<tr>
												<th>Docker</th>
												<th>Host port</th>
												<th>Docker port</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>php</th>
												<td>-</td>
												<td>9000</td>
											</tr>
											<tr>
												<th>httpd</th>
												<td><?php echo loadClass('Docker')->getEnv('LOCAL_LISTEN_ADDR').loadClass('Docker')->getEnv('HOST_PORT_HTTPD');?></td>
												<td>80</td>
											</tr>
											<?php if ($avail_mysql): ?>
												<tr>
													<th>mysql</th>
													<td><?php echo loadClass('Docker')->getEnv('LOCAL_LISTEN_ADDR').loadClass('Docker')->getEnv('HOST_PORT_MYSQL');?></td>
													<td>3306</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_pgsql): ?>
												<tr>
													<th>pgsql</th>
													<td><?php echo loadClass('Docker')->getEnv('LOCAL_LISTEN_ADDR').loadClass('Docker')->getEnv('HOST_PORT_PGSQL');?></td>
													<td>5432</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_redis): ?>
												<tr>
													<th>redis</th>
													<td><?php echo loadClass('Docker')->getEnv('LOCAL_LISTEN_ADDR').loadClass('Docker')->getEnv('HOST_PORT_REDIS');?></td>
													<td>6379</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_memcd): ?>
												<tr>
													<th>memcached</th>
													<td><?php echo loadClass('Docker')->getEnv('LOCAL_LISTEN_ADDR').loadClass('Docker')->getEnv('HOST_PORT_MEMCACHED');?></td>
													<td>11211</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> Socket volumes</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small">
										<thead class="thead-inverse">
											<tr>
												<th>Host Volume</th>
												<th>Docker</th>
												<th>Docker path</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td rowspan="2" class="align-middle">vol_mysql_sock</td>
												<th>php</th>
												<td>/tmp/mysql</td>
											</tr>
											<tr>
												<td>mysql</td>
												<td>/tmp/mysql</td>
											</tr>
											<tr>
												<td rowspan="2" class="align-middle">vol_pqsql_sock</td>
												<th>php</th>
												<td>-</td>
											</tr>
											<tr>
												<td>pgsql</td>
												<td>/var/run/postgresql</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-hdd-o" aria-hidden="true"></i> Data mounts</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small" style="word-break: break-word;">
										<thead class="thead-inverse">
											<tr>
												<th>Docker</th>
												<th>Host path</th>
												<th>Docker path</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>php</th>
													<td><?php echo loadClass('Docker')->getEnv('HOST_PATH_HTTPD_DATADIR'); ?></td>
												<td>/shared/httpd</td>
											</tr>
											<tr>
												<th>httpd</th>
													<td><?php echo loadClass('Docker')->getEnv('HOST_PATH_HTTPD_DATADIR'); ?></td>
												<td>/shared/httpd</td>
											</tr>
											<?php if ($avail_mysql): ?>
												<tr>
													<th>mysql</th>
													<td><?php echo loadClass('Docker')->getEnv('HOST_PATH_MYSQL_DATADIR').'/'.loadClass('Docker')->getEnv('MYSQL_SERVER'); ?></td>
													<td>/var/lib/mysql</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_pgsql): ?>
												<tr>
													<th>pgsql</th>
													<td><?php echo loadClass('Docker')->getEnv('HOST_PATH_PGSQL_DATADIR').'/'.loadClass('Docker')->getEnv('PGSQL_SERVER'); ?></td>
													<td>/var/lib/postgresql/data/pgdata</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_redis): ?>
												<tr>
													<th>redis</th>
													<td>-</td>
													<td>-</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_memcd): ?>
												<tr>
													<th>memcached</th>
													<td>-</td>
													<td>-</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-cogs" aria-hidden="true"></i> Config mounts</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small">
										<thead class="thead-inverse">
											<tr>
												<th>Docker</th>
												<th>Host path</th>
												<th>Docker path</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>php</th>
												<td>./cfg/<?php echo loadClass('Docker')->getEnv('PHP_SERVER'); ?></td>
												<td>/etc/php-custom.d</td>
											</tr>
											<tr>
												<th>httpd</th>
												<td>-</td>
												<td>-</td>
											</tr>
											<?php if ($avail_mysql): ?>
												<tr>
													<th>mysql</th>
													<td>./cfg/<?php echo loadClass('Docker')->getEnv('MYSQL_SERVER'); ?></td>
													<td>/etc/mysql/conf.d</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_pgsql): ?>
												<tr>
													<th>pgsql</th>
													<td>-</td>
													<td>-</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_redis): ?>
												<tr>
													<th>redis</th>
													<td>-</td>
													<td>-</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_memcd): ?>
												<tr>
													<th>memcached</th>
													<td>-</td>
													<td>-</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12 col-margin">
					<div class="dash-box">
						<div class="dash-box-head"><i class="fa fa-bar-chart" aria-hidden="true"></i> Log mounts</div>
						<div class="dash-box-body">
							<div class="row">
								<div class="container">
									<table class="table table-striped table-hover table-bordered table-sm font-small" style="word-break: break-word;">
										<thead class="thead-inverse">
											<tr>
												<th>Docker</th>
												<th>Host path</th>
												<th>Docker path</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<th>php</th>
												<td>./log/<?php echo loadClass('Docker')->getEnv('PHP_SERVER'); ?></td>
												<td>/var/log/php</td>
											</tr>
											<tr>
												<th>httpd</th>
												<td>./log/<?php echo loadClass('Docker')->getEnv('HTTPD_SERVER'); ?></td>
												<td>/var/log/<?php echo loadClass('Docker')->getEnv('HTTPD_SERVER'); ?></td>
											</tr>
											<?php if ($avail_mysql): ?>
												<tr>
													<th>mysql</th>
													<td>./log/<?php echo loadClass('Docker')->getEnv('MYSQL_SERVER'); ?></td>
													<td>/var/log/mysql</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_pgsql): ?>
												<tr>
													<th>pgsql</th>
													<td>./log/pgsql-<?php echo loadClass('Docker')->getEnv('PGSQL_SERVER'); ?></td>
													<td>/var/log/postgresql</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_redis): ?>
												<tr>
													<th>redis</th>
													<td>./log/redis-<?php echo loadClass('Docker')->getEnv('REDIS_SERVER'); ?></td>
													<td>/var/log/redis</td>
												</tr>
											<?php endif; ?>
											<?php if ($avail_memcd): ?>
												<tr>
													<th>memcached</th>
													<td>./log/memcached-<?php echo loadClass('Docker')->getEnv('MEMCACHED_SERVER'); ?></td>
													<td>/var/log/memcached</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>


			</div><!-- /row -->


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
