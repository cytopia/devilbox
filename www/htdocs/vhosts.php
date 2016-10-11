<?php require '../config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="favicon.ico">
		<link href="/assets/css/custom.css" rel="stylesheet">

		<title>DevilBox</title>
	</head>

	<body>

		<nav class="navbar navbar-inverse navbar-static-top">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<span class="navbar-brand" href="#">DevilBox</span>
				</div>
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="/">Home</a></li>
						<li class="active"><a href="#">Virtual Hosts</a></li>
						<li><a href="/databases.php">Databases</a></li>
						<li><a href="/php.php">PHP</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>

		<div class="container">

			<h1>Virtual Hosts</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php $vHosts = getVirtualHosts(); ?>
					<?php if ($vHosts): ?>
						<table class="table table-striped">
							<thead>
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
										<td><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?>/<?php echo $vHost['name'];?>/htdocs</td>
										<td id="valid-<?php echo $vHost['name'];?>">&nbsp;&nbsp;&nbsp;</td>
										<td id="href-<?php echo $vHost['name'];?>"><?php echo $filler;?></td>
									</tr>
									<input type="hidden" name="vhost[]" class="vhost" value="<?php echo $vHost['name'];?>" />
								<?php endforeach; ?>
							</tbody>
						</table>
					<?php else: ?>
						<h4>No projects here.</h4>
						<p>Simply create a folder in <strong><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?></strong> (on your host computer - not inside the docker).</p>
						<p><strong>Example:</strong><br/><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?>/my_project</p>
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
							el_valid.className += ' danger';
							el_valid.innerHTML = 'ERR';
							el_href.innerHTML = error;
						} else {
							el_valid.className += ' success';
							el_valid.innerHTML = 'OK';
							el_href.innerHTML = '<a target="_blank" href="http://'+vhost+'.loc">'+vhost+'.loc</a>';
						}
					}
				};
				xhttp.open('GET', '_ajax_vhost.php?valid=' + vhost, true);
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
