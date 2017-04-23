<?php require '../config.php'; ?>
<?php if (loadClass('Docker')->getEnv('COMPOSE_OPTIONAL') != 1) {
	header('Location: /index.php');
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

			<h1>Redis Keys</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<table class="table table-striped ">
						<thead class="thead-inverse ">
							<tr>
								<th>Key</th>
								<th>Value</th>
							</th>
						</thead>
						<tbody>
							<?php foreach (loadClass('Redis')->getKeys() as $key => $value): ?>
								<tr>
									<td><?php echo $key;?></td>
									<td><?php print_r($value);?></td>
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
