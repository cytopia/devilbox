<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navbar.php'; ?>

		<div class="container">

			<h1>Virtual Hosts</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php $vHosts = $Docker->PHP_getVirtualHosts(); ?>
					<?php if ($vHosts): ?>
						<table class="table table-striped">
							<thead class="thead-inverse">
								<tr>
									<th>Project</th>
									<th>DocumentRoot</th>
									<th>Valid</th>
									<th>URL</th>
								</th>
							</thead>
							<tbody>
								<?php
									$totals = 70;
									$filler = '&nbsp;';
									for ($i=0; $i<$totals; $i++) {
										$filler = $filler. '&nbsp;';
									}
								?>
								<?php foreach ($vHosts as $vHost): ?>
									<tr>
										<td><?php echo $vHost['name'];?></td>
										<td><?php echo $Docker->getEnv('HOST_PATH_TO_WWW_DOCROOTS');?>/<?php echo $vHost['name'];?>/htdocs</td>
										<td class="text-xs-center text-xs-small" id="valid-<?php echo $vHost['name'];?>">&nbsp;&nbsp;&nbsp;</td>
										<td id="href-<?php echo $vHost['name'];?>"><?php echo $filler;?></td>
									</tr>
									<input type="hidden" name="vhost[]" class="vhost" value="<?php echo $vHost['name'];?>" />
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<h4>No projects here.</h4>
						<p>Simply create a folder in <strong><?php echo $Docker->getEnv('HOST_PATH_TO_WWW_DOCROOTS');?></strong> (on your host computer - not inside the docker).</p>
						<p><strong>Example:</strong><br/><?php echo $Docker->getEnv('HOST_PATH_TO_WWW_DOCROOTS');?>/my_project</p>
					<?php endif;?>
				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
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

					if (this.readyState == 4 && this.status == 200) {
						el_valid = document.getElementById('valid-' + vhost);
						el_href = document.getElementById('href-' + vhost);
						error = this.responseText;

						if (error.length) {
							el_valid.className += ' bg-danger';
							el_valid.innerHTML = 'ERR';
							el_href.innerHTML = error;
						} else {
							el_valid.className += ' bg-success';
							el_valid.innerHTML = 'OK';
							el_href.innerHTML = '<a target="_blank" href="http://'+vhost+'.<?php echo $Docker->getTld().$Docker->getPort();?>">'+vhost+'.<?php echo $Docker->getTld().$Docker->getPort();?></a>';
						}
					}
				};
				xhttp.open('GET', '_ajax_callback.php?vhost=' + vhost, true);
				xhttp.send();
			}


			var vhosts = document.getElementsByName('vhost[]');

			for (i = 0; i < vhosts.length; i++) {
				updateStatus(vhosts[i].value);
			}

		})();
		</script>
	</body>
</html>
