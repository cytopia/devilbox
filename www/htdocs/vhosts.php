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
					<?php $vHosts = getVhosts(); ?>
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
								<?php foreach ($vHosts as $vHost): ?>
									<?php
										$err = array();
										$ern = 0;

										if (!$vHost['htdocs']) {
											$err[] = 'Missing <strong>htdocs</strong> directory in: <strong>'.$ENV['HOST_PATH_TO_WWW_DOCROOTS'].'/'.$vHost['name'].'/</strong>';
											$ern++;
										}
										if ($vHost['dns_ok']) {

											if ($vHost['dns_ip'] != '127.0.0.1') {
												$err[] = 'Error in <strong>/etc/hosts</strong><br/>'.
														'Found:<br/>'.
														'<code>'.$vHost['dns_ip'].' '.$vHost['domain'].'</code><br/>'.
														'But it should be:<br/>'.
														'<code>127.0.0.1 '.$vHost['domain'].'</code><br/>';
												$ern++;

											}
										} else {
											$err[] = 'Missing entry in <strong>/etc/hosts</strong>:<br/><code>127.0.0.1 '.$vHost['domain'].'</code>';
											$ern++;
										}
									?>
									<tr>
										<td><?php echo $vHost['name'];?></td>
										<td><?php echo $ENV['HOST_PATH_TO_WWW_DOCROOTS'];?>/<?php echo $vHost['name'];?>/htdocs</td>
										<?php if ($ern): ?>
											<td colspan="2">
												<?php echo implode('<br/>', $err); ?>
											</td>
										<?php else: ?>
											<td>OK</td>
											<td><a target="_blank" href="<?php echo $vHost['href'];?>"><?php echo $vHost['domain'];?></a></td>
										<?php endif; ?>
									</tr>
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

	</body>
</html>
