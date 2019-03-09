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

			<h1>Credits</h1>
			<br/>
			<br/>

			<div class="row">

				<div class="col-md-6">
					<h2>Contributors</h2>
					<p>The following people have contributed to the <a href="https://github.com/cytopia/devilbox/graphs/contributors">Devilbox</a>.</p>
					<table class="table table-striped ">
						<thead class="thead-inverse ">
							<tr>
								<th>Contributor</th>
								<th>Contributions</th>
								<th>Url</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>cytopia</td>
								<td>Project creator</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a href="https://github.com/cytopia">cytopia</a></td>
							</tr>
							<tr>
								<td>Maifz</td>
								<td>Logos</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a href="https://github.com/Maifz">Maifz</a></td>
							</tr>
						</tbody>
					</table>
					<p>If you like to contribute, have a lookt at the <a href="https://github.com/cytopia/devilbox/blob/master/CONTRIBUTING.md">Contributing Guidelines</a>.</p>
				</div>


				<div class="col-md-6">
					<h2>Libraries</h2>
					<p>The <a href="https://github.com/cytopia/devilbox">Devilbox</a> includes several libraries which are listed below.</p>
					<table class="table table-striped ">
						<thead class="thead-inverse ">
							<tr>
								<th>Vendor</th>
								<th>License</th>
								<th>Url</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Adminer</td>
								<td>Apache License 2.0 or GPL 2</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/vrana/adminer">vrana/adminer</a></td>
							</tr>
							<tr>
								<td>Bootstrap</td>
								<td>MIT</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/twbs/bootstrap">twbs/bootstrap</a></td>
							</tr>
							<tr>
								<td>Font Awesome (css)</td>
								<td>MIT</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/FortAwesome/Font-Awesome">FortAwesome/Font-Awesome</a></td>
							</tr>
							<tr>
								<td>Font Awesome (fonts)</td>
								<td>SIL OFL 1.1</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/FortAwesome/Font-Awesome">FortAwesome/Font-Awesome</a></td>
							</tr>
							<tr>
								<td>Opcache GUI</td>
								<td>MIT</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/amnuts/opcache-gui">amnuts/opcache-gui</a></td>
							</tr>
							<tr>
								<td>PHPMemcachedAdmin</td>
								<td>Apache 2.0</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/elijaa/phpmemcachedadmin">elijaa/phpmemcachedadmin</a></td>
							</tr>
							<tr>
								<td>phpMyAdmin</td>
								<td>GPL 2.0</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/phpmyadmin/phpmyadmin">phpmyadmin/phpmyadmin</a></td>
							</tr>
							<tr>
								<td>phpPgAdmin</td>
								<td>GPL 2.0</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/phppgadmin/phppgadmin">phppgadmin/phppgadmin</a></td>
							</tr>
							<tr>
								<td>PHPRedMin</td>
								<td>BSD 3-Clause License</td>
								<td><i class="fa fa-github-alt" aria-hidden="true"></i> <a target="_blank" href="https://github.com/sasanrose/phpredmin">sasanrose/phpredmin</a></td>
							</tr>
						</tbody>
					</table>
				</div>

			</div>

		</div><!-- /.container -->

		<?php echo loadClass('Html')->getFooter(); ?>
	</body>
</html>
