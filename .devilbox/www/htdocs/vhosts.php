<?php require '../config.php'; ?>
<?php loadClass('Helper')->authPage(); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo loadClass('Html')->getHead(true); ?>
	</head>

	<body>
		<?php echo loadClass('Html')->getNavbar(); ?>

		<div class="container">

			<h1>Virtual Hosts</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php $vHosts = loadClass('Httpd')->getVirtualHosts(); ?>
					<?php if ($vHosts): ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Project</th>
									<th>DocumentRoot</th>
									<th>Backend</th>
									<th>Config</th>
									<th style="width:60px;">Valid</th>
									<th style="width:260px;">URL</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($vHosts as $vHost): ?>
									<tr>
										<td><?php echo $vHost['name'];?></td>
										<td><?php echo loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR');?>/<?php echo $vHost['name'];?>/<?php echo loadClass('Helper')->getEnv('HTTPD_DOCROOT_DIR');?></td>
										<td><?php echo loadClass('Httpd')->getVhostBackend($vHost['name']); ?></td>
										<td>
											<?php $id_vhost_httpd    = str_replace('=', '', base64_encode('vhost_httpd_conf_' . $vHost['name'])); ?>
											<?php $id_vhost_vhostgen = str_replace('=', '', base64_encode('vhost_vhost_gen_' . $vHost['name'])); ?>

											<!-- [httpd.conf] Button trigger modal -->
											<a href="#"><i class="fa fa-cog" aria-hidden="true" data-toggle="modal" data-target="#<?php echo $id_vhost_httpd;?>"></i></a>
											<!-- [httpd.conf] Modal -->
											<div class="modal" id="<?php echo $id_vhost_httpd;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id_vhost_httpd;?>Label" aria-hidden="true">
												<div class="modal-dialog modal-lg" role="document">
													<div class="modal-content">
														<div class="modal-header">
															<h5 class="modal-title" id="<?php echo $id_vhost_httpd;?>Label"><?php echo '<strong>httpd.conf: </strong><code>'.$vHost['name'].'.'.loadClass('Httpd')->getTldSuffix(). '</code>'; ?></h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															<span aria-hidden="true">&times;</span>
															</button>
														</div>
														<div class="modal-body">
															<?php $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"; ?>
															<?php $src = file_get_contents($url.'/vhost.d/' . $vHost['name'] . '.conf'); ?>
															<?php //$src = htmlentities($src); ?>
															<?php $src = str_replace('<', '&lt;', $src); ?>
															<?php $src = str_replace('>', '&gt;', $src); ?>
															<?php $src = preg_replace('/&lt;(\/?.*)&gt;/m', '<strong>&lt;\1&gt;</strong>', $src); // Apache directives ?>
															<?php $src = preg_replace('/(.*{\s*)$/m',       '<strong>\1</strong>',         $src); // Nginx directives ?>
															<?php $src = preg_replace('/^(\s*}\s*)$/m',     '<strong>\1</strong>',         $src); // Nginx directives ?>
															<?php //$src = preg_replace('/"(.+)"/m',       '<span style="color: blue;">"\1"</span>', $src); ?>
															<?php $src = preg_replace('/^(\s*(?!<#)[^#"]*)"(.*)"/m',  '\1<span style="color: blue;">"\2"</span>', $src);  // double quotes?>
															<?php $src = preg_replace("/^(\s*(?!<#)[^#']*)'(.*)'/m",  '\1<span style="color: blue;">"\2"</span>', $src);  // single quotes ?>
															<?php $src = preg_replace('/^(\s*#)(.*)$/m',              '<span style="color: gray;">\1\2</span>',   $src);  // comments ?>
															<?php echo '<pre><code>' . $src . '</code></pre>';?>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>
											<?php if (($vhostGenPath = loadClass('Httpd')->getVhostgenTemplatePath($vHost['name'])) !== false): ?>
												<!-- [vhost-gen] Button trigger modal -->
												<a href="#"><i class="fa fa-filter" aria-hidden="true" data-toggle="modal" data-target="#<?php echo $id_vhost_vhostgen;?>"></i></a>
												<!-- [vhost-gen] Modal -->
												<div class="modal" id="<?php echo $id_vhost_vhostgen;?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $id_vhost_vhostgen;?>Label" aria-hidden="true">
													<div class="modal-dialog modal-lg" role="document">
														<div class="modal-content">
															<div class="modal-header">
																<h5 class="modal-title" id="<?php echo $id_vhost_vhostgen;?>Label"><?php echo '<code>'.$vhostGenPath.'</code>'; ?></h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																<span aria-hidden="true">&times;</span>
																</button>
															</div>
															<div class="modal-body">
																<?php $src = file_get_contents($vhostGenPath); ?>
																<?php //$src = htmlentities($src); ?>
																<?php $src = str_replace('<', '&lt;', $src); ?>
																<?php $src = str_replace('>', '&gt;', $src); ?>
																<?php $src = preg_replace('/&lt;(\/?.*)&gt;/m', '<strong>&lt;\1&gt;</strong>', $src); // Apache directives ?>
																<?php $src = preg_replace('/(.*{\s*)$/m',       '<strong>\1</strong>',         $src); // Nginx directives ?>
																<?php $src = preg_replace('/^(\s*}\s*)$/m',     '<strong>\1</strong>',         $src); // Nginx directives ?>
																<?php //$src = preg_replace('/"(.+)"/m',        '<span style="color: blue;">"\1"</span>', $src); ?>
																<?php //$src = preg_replace("/'(.+)'/m",        '<span style="color: blue;">'."'".'\1'."'".'</span>', $src); ?>
																<?php $src = preg_replace('/^(\s*(?!<#)[^#"]*)"(.*)"/m', '\1<span style="color: blue;">"\2"</span>',                $src); // double quotes ?>
																<?php $src = preg_replace("/^(\s*(?!<#)[^#']*)'(.*)'/m", '\1<span style="color: blue;">"\2"</span>',                $src); // single quotes ?>
																<?php $src = preg_replace('/^(\s*#)(.*)$/m',             '<span style="color: gray;">\1\2</span>',                  $src); // comments ?>
																<?php $src = preg_replace('/^(\s*[_a-z]+):/m',           '<span style="color: green;"><strong>\1</strong></span>:', $src); // yaml keys ?>
																<?php $src = preg_replace('/(__[_A-Z]+__)/m',            '<span style="color: red;">\1</span>',                     $src); // variables ?>
																<?php echo '<pre><code>' . $src . '</code></pre>';?>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															</div>
														</div>
													</div>
												</div>
											<?php endif; ?>
										</td>
										<td class="text-xs-center text-xs-small" id="valid-<?php echo $vHost['name'];?>"></td>
										<td id="href-<?php echo $vHost['name'];?>"></td>
									</tr>
									<input type="hidden" name="vhost[]" class="vhost" value="<?php echo $vHost['name'];?>" />
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<h4>No projects here.</h4>
						<p>Simply create a directory in <strong><?php echo loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR');?></strong> on your host computer (or in <strong>/shared/httpd</strong> inside the php container).</p>
						<p><strong>Example:</strong><br/><?php echo loadClass('Helper')->getEnv('HOST_PATH_HTTPD_DATADIR');?>/my_project</p>
						<p>It will then be available via <strong>http://my_project.<?php echo loadClass('Httpd')->getTldSuffix();?></strong></p>
					<?php endif;?>
				</div>
			</div>

			<?php
			$cmd="netstat -wneeplt 2>/dev/null | sort | grep '\s1000\s' | awk '{print \"app=\"\$9\"|addr=\"\$4}' | sed 's/\(app=\)\([0-9]*\/\)/\\1/g' | sed 's/\(.*\)\(:[0-9][0-9]*\)/\\1|port=\\2/g' | sed 's/port=:/port=/g'";
			$output=loadClass('Helper')->exec($cmd);
			$daemons = array();
			foreach (preg_split("/((\r?\n)|(\r\n?))/", $output) as $line) {
				$section = preg_split("/\|/", $line);
				if (count($section) == 3) {
					$tool = preg_split("/=/", $section[0]);
					$addr = preg_split("/=/", $section[1]);
					$port = preg_split("/=/", $section[2]);
					$tool = $tool[1];
					$addr = $addr[1];
					$port = $port[1];
					$daemons[] = array(
						'tool' => $tool,
						'addr' => $addr,
						'port' => $port
					);
				}
			}
			?>
			<?php if (count($daemons)): ?>
			<br/>
			<br/>
			<div class="row">
				<div class="col-md-12">

					<h2>Local listening daemons</h2>
					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th>Application</th>
								<th>Listen Address</th>
								<th>Listen Port</th>
								<th>Host</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($daemons as $daemon): ?>
								<tr>
									<td><?php echo $daemon['tool']; ?></td>
									<td><?php echo $daemon['addr']; ?></td>
									<td><?php echo $daemon['port']; ?></td>
									<td>php (172.16.238.10)</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
			<?php endif; ?>



		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here


			function updateStatus(vhost) {
				var xhttp = new XMLHttpRequest();

				xhttp.onreadystatechange = function() {
					var error = '';
					var el_valid;
					var el_href;

					if (this.readyState == 4 && this.status == 200 || this.status == 426) {
						el_valid = document.getElementById('valid-' + vhost);
						el_href = document.getElementById('href-' + vhost);
						error = this.responseText;

						if (error.length && error.match(/^error/)) {
							el_valid.className += ' bg-danger';
							el_valid.innerHTML = 'ERR';
							el_href.innerHTML = error;
						} else if (error.length && error.match(/^warning/)) {
							el_valid.className += ' bg-warning';
							el_valid.innerHTML = 'WARN';
							el_href.innerHTML = error.replace('warning', '');
							checkDns(vhost);
						} else {
							checkDns(vhost);
						}
					}
				};
				xhttp.open('GET', '_ajax_callback.php?vhost=' + vhost, true);
				xhttp.send();
			}

			/**
			 * Check if DNS record is set in /etc/hosts (or via attached DNS server)
			 * for TLD_SUFFIX
			 */
			function checkDns(vhost) {
				var xhttp = new XMLHttpRequest();
				var proto;
				var port;
				var name = vhost + '.<?php echo loadClass('Httpd')->getTldSuffix();?>'

				var url = window.location.href.split("/");
				var tmp = url[2].split(":");
				proto = url[0];
				port = tmp.length == 2 ? ':' + tmp[1] : '';

				// Timeout after XXX seconds and mark it invalid DNS
				xhttp.timeout = <?php echo loadClass('Helper')->getEnv('DNS_CHECK_TIMEOUT');?>000;

				xhttp.onreadystatechange = function(e) {
					var el_valid = document.getElementById('valid-' + vhost);
					var el_href = document.getElementById('href-' + vhost);
					var error = this.responseText;

					if (this.readyState == 4 && (this.status == 200 || this.status == 426)) {
						clearTimeout(xmlHttpTimeout);
						el_valid.className += ' bg-success';
						if (el_valid.innerHTML != 'WARN') {
							el_valid.innerHTML = 'OK';
						}
						//el_href.innerHTML = '(<a target="_blank" href="'+proto+'//localhost/devilbox-project/'+name+'">ext</a>) <a target="_blank" href="'+proto+'//'+name+port+'">'+name+port+'</a>' + el_href.innerHTML;
						el_href.innerHTML = '<a target="_blank" href="'+proto+'//'+name+port+'">'+name+port+'</a>';
					} else {
						//console.log(vhost);
					}
				}
				xhttp.open('POST', proto+'//'+name+port+'/devilbox-api/status.json', true);
				xhttp.send();

				// Timeout to abort in 1 second
				var xmlHttpTimeout = setTimeout(ajaxTimeout, <?php echo loadClass('Helper')->getEnv('DNS_CHECK_TIMEOUT');?>000);
				function ajaxTimeout(e) {
					var el_valid = document.getElementById('valid-' + vhost);
					var el_href = document.getElementById('href-' + vhost);
					var error = this.responseText;

					el_valid.className += ' bg-danger';
					el_valid.innerHTML = 'ERR';
					el_href.innerHTML = 'No Host DNS record found. Add the following to <code>/etc/hosts</code>:<br/><code>127.0.0.1 '+vhost+'.<?php echo loadClass('Httpd')->getTldSuffix();?></code>';
				}

			}

			var vhosts = document.getElementsByName('vhost[]');

			for (i = 0; i < vhosts.length; i++) {
				updateStatus(vhosts[i].value);
			}
		})();


		</script>
	</body>
</html>
