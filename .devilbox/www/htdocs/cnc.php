<?php require '../config.php';  ?>
<?php loadClass('Helper')->authPage(); ?>
<?php
// TODO: This is currently a temporary hack to talk to supervisor on the HTTPD server
function run_supervisor_command($host, $command) {
	if ($host == 'httpd') {
		$supervisor_config_file = '/tmp/supervisorctl.conf';
		$port = getenv('SVCTL_LISTEN_PORT');
		$user = getenv('SVCTL_USER');
		$pass = getenv('SVCTL_PASS');

		$content = "[supervisorctl]\n";
		$content .= "serverurl=http://httpd:" . $port . "\n";
		$content .= "username=" . $user . "\n";
		$content .= "password=" . $pass . "\n";

		$fp = fopen($supervisor_config_file, 'w');
		fwrite($fp, $content);
		fclose($fp);

		return loadClass('Helper')->exec('supervisorctl -c ' . $supervisor_config_file . ' ' . $command);
	} else if ($host == 'php') {
		return loadClass('Helper')->exec('sudo supervisorctl ' . $command);
	}
}


?>
<?php
	if ( isset($_POST['watcherd']) && $_POST['watcherd'] == 'reload' ) {
		run_supervisor_command('httpd', 'restart watcherd');
		sleep(1);
		loadClass('Helper')->redirect('/cnc.php');
	} else if ( isset($_POST['php-fpm']) && $_POST['php-fpm'] == 'reload' ) {
		run_supervisor_command('php', 'restart php-fpm');
		loadClass('Helper')->redirect('/cnc.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">
			<h1>Command & Control</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php
						$status_w = run_supervisor_command('httpd', 'status watcherd');
						$status_h = run_supervisor_command('httpd', 'status httpd');
						$status_p = run_supervisor_command('php',   'status php-fpm');

						$words = preg_split("/\s+/", $status_w);
						$data_w = array(
							'name'   => isset($words[0]) ? $words[0] : '',
							'state'  => isset($words[1]) ? $words[1] : '',
							'pid'    => isset($words[3]) ? preg_replace('/,$/', '', $words[3]) : '',
							'uptime' => isset($words[5]) ? $words[5] : '',
						);
						$words = preg_split("/\s+/", $status_h);
						$data_h = array(
							'name'   => isset($words[0]) ? $words[0] : '',
							'state'  => isset($words[1]) ? $words[1] : '',
							'pid'    => isset($words[3]) ? preg_replace('/,$/', '', $words[3]) : '',
							'uptime' => isset($words[5]) ? $words[5] : '',
						);
						$words = preg_split("/\s+/", $status_p);
						$data_p = array(
							'name'   => isset($words[0]) ? $words[0] : '',
							'state'  => isset($words[1]) ? $words[1] : '',
							'pid'    => isset($words[3]) ? preg_replace('/,$/', '', $words[3]) : '',
							'uptime' => isset($words[5]) ? $words[5] : '',
						);
					?>
					<h3>Daemon overview</h3><br/>
					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th>Daemon</th>
								<th>Container</th>
								<th>Status</th>
								<th>Pid</th>
								<th>Uptime</th>
								<th>Info</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><code><?php echo $data_p['name']; ?></code></td>
								<td>php</td>
								<td><?php echo $data_p['state']; ?></td>
								<td><?php echo $data_p['pid']; ?></td>
								<td><?php echo $data_p['uptime']; ?></td>
								<td><a href="#"><i class="fa fa-info" aria-hidden="true" data-toggle="modal" data-target="#info-php-fpm"></i></a></td>
								<td><form method="post"><button type="submit" name="php-fpm" value="reload" class="btn btn-primary">Reload</button></form></td>
							</tr>
							<tr>
								<td><code><?php echo $data_w['name']; ?></code></td>
								<td>httpd</td>
								<td><?php echo $data_w['state']; ?></td>
								<td><?php echo $data_w['pid']; ?></td>
								<td><?php echo $data_w['uptime']; ?></td>
								<td><a href="#"><i class="fa fa-info" aria-hidden="true" data-toggle="modal" data-target="#info-watcherd"></i></a></td>
								<td><form method="post"><button type="submit" name="watcherd" value="reload" class="btn btn-primary">Reload</button></form></td>
							</tr>
							<tr>
								<td><code><?php echo $data_h['name']; ?></code></td>
								<td>httpd</td>
								<td><?php echo $data_h['state']; ?></td>
								<td><?php echo $data_h['pid']; ?></td>
								<td><?php echo $data_h['uptime']; ?></td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<br/>
					<br/>

					<h3>watcherd stderr</h3>
					<br/>
					<?php
						$output = run_supervisor_command('httpd', 'tail -1000000 watcherd stderr');
						echo '<pre>';
						foreach(preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
							if ( strpos($line, "[ERR]") !== false ) {
								echo '<span style="color: #ff0000">' . $line . '</span>';
							} else if ( strpos($line, "[emerg]") !== false ) {
								echo '<span style="color: #ff0000">' . $line . '</span>';
							} else if ( strpos($line, "Syntax error") !== false ) {
								echo '<span style="color: #ff0000">' . $line . '</span>';
							} else if ( strpos($line, "[WARN]") !== false ) {
								echo '<span style="color: #ccaa00">' . $line . '</span>';
							} else {
								echo $line;
							}
							echo "\n";
						}
						echo '</pre>';
					?>
					<h3>watcherd stdout</h3>
					<br/>
					<?php
						$output = run_supervisor_command('httpd', 'tail -1000000 watcherd');
						echo '<pre>';
						foreach(preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
							$pos_info = strpos($line, "[INFO]");
							$pos_ok   = strpos($line, "[OK]");
							if ( $pos_ok !== false ) {
								echo '<span style="color: #669a00"><strong>' . $line . '</strong></span>';
							} else if ( $pos_info !== false && $pos_info == 0 ) {
								echo '<span style="color: #0088cd">' . $line . '</span>';
							} else {
								echo $line;
							}
							echo "\n";
						}
						echo '</pre>';
					?>

				</div>
			</div>

		</div><!-- /.container -->

		<!-- Modals (php-fpm) -->
		<div class="modal fade" id="info-php-fpm" tabindex="-1" role="dialog" aria-labelledby="info-php-fpmLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="info-php-fpmLabel">Reload <code>php-fpm</code></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<p>If you made any changes to PHP such as adding/altering <code>php.ini</code> or <code>php-fpm.conf</code> configuration files (e.g.: Xdebug or other settings), you can trigger a manual restart of the <code>php-fpm</code> process to apply your changes.</p>
				<p>Reloading php-fpm will apply those changes immediately. There is no need to restart docker compose.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>
		<!-- Modals (php-fpm) -->
		<div class="modal fade" id="info-watcherd" tabindex="-1" role="dialog" aria-labelledby="info-watcherdLabel" aria-hidden="true">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="info-watcherdLabel">Reload <code>watcherd</code></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body">
				<p>If you have made any changes to vhost settings such as adding/altering custom vhost-gen templates, backend configuration or changes to the webserver itself, you can trigger a manual reload of <code>watcherd</code>.</p>
				<p>Reloading watcherd will update all vhosts based and your configuration and reload the webserver. There is no need to restart docker compose.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			  </div>
			</div>
		  </div>
		</div>

		<?php echo loadClass('Html')->getFooter(); ?>
		<script>
		$(function() {
			$('.subject').click(function() {
				const id = ($(this).attr('id'));
				const row = $('#mail-'+id);
				row.toggle();

				const bodyElement = row.find('.email-body')[0];
				if(bodyElement.shadowRoot !== null){
					// We've already fetched the message content.
					return;
				}

				bodyElement.attachShadow({ mode: 'open' });
				bodyElement.shadowRoot.innerHTML = 'Loading...';

				$.get('?get-body=' + id, function(response){
					response = JSON.parse(response);
					row.find('.raw-email-body').html(response.raw);

					const body = response.body;
					if(body === null){
						row.find('.alert').show();
					}
					else{
						bodyElement.shadowRoot.innerHTML = body;
					}
				})
			})
			// Handler for .ready() called.
		});
		</script>
	</body>
</html>
