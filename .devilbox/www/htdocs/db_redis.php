<?php require '../config.php'; ?>
<?php $Postgres = loadClass('Redis'); ?>
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

					<?php if (!\devilbox\Redis::isAvailable($GLOBALS['REDIS_HOST_NAME'])): ?>
						<p>Redis container is not running.</p>
					<?php else: ?>
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
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>

	</body>
</html>
