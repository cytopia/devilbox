<?php require '../config.php'; ?>
<?php $Docker = loadClass('Docker'); ?>
<?php if ($Docker->getEnv('COMPOSE_OPTIONAL') != 1 ) {
	header();
	exit(0);
} ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navbar.php'; ?>

		<div class="container">

			<h1>Redis Info</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<table class="table table-striped">
						<thead class="thead-inverse">
							<tr>
								<th>Variable</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach (loadClass('Redis')->getInfo() as $key => $val): ?>
								<tr>
									<td><?php echo $key;?></td>
									<td class="break-word"><code><?php echo $val;?></code></td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>

				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
	</body>
</html>
