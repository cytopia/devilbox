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
						<li><a href="/vhosts.php">Virtual Hosts</a></li>
						<li class="active"><a href="#">Databases</a></li>
						<li><a href="/php.php">PHP</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</nav>

		<div class="container">

			<h1>Databases</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">
					<?php
						$databases = getDatabases();
					?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Charset</th>
								<th>Collation</th>
								<th>Size</th>
							</th>
						</thead>
						<tbody>
							<?php foreach ($databases as $name => $keys): ?>
								<tr>
									<td><?php echo $name;?></td>
									<td><?php echo $keys['charset'];?></td>
									<td><?php echo $keys['collation'];?></td>
									<td><span class="size" id="<?php echo $name;?>"></span> MB</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>

		</div><!-- /.container -->


		<script>
		// self executing function here
		(function() {
			// your page initialization code here
			// the DOM will be available here

			function updateSizes(database) {
				var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
					if (this.readyState == 4 && this.status == 200) {
						document.getElementById(database).innerHTML = this.responseText;
						console.log(this.responseText);
					}
				};
				xhttp.open('GET', '_ajax_db_size.php?db=' + database, true);
				xhttp.send();
			}

			var databases = document.getElementsByClassName('size');
			var database;
			for (i = 0; i < databases.length; i++) {
				database = databases[i].getAttribute('id');
				updateSizes(database);
			}
		})();
		</script>

	</body>
</html>
