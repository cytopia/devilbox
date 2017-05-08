<?php require '../config.php'; ?>
<?php $Postgres = loadClass('Memcd'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require '../include/head.php'; ?>
	</head>

	<body>
		<?php require '../include/navbar.php'; ?>

		<div class="container">

			<h1>Memcached Info</h1>
			<br/>
			<br/>

			<div class="row">
				<div class="col-md-12">

					<?php if (!\devilbox\Memcd::isAvailable($GLOBALS['MEMCD_HOST_NAME'])): ?>
						<p>Memcahed container is not running.</p>
					<?php else: ?>
						<?php foreach (loadClass('Memcd')->getInfo() as $srv => $data): ?>
							<h2><?php echo $srv; ?></h2>
							<table class="table table-striped">
								<thead class="thead-inverse">
									<tr>
										<th>Variable</th>
										<th>Value</th>
									</tr>
								</thead>
								<tbody>
										<?php foreach ($data as $key => $val): ?>
										<tr>
											<td><?php echo $key;?></td>
											<td class="break-word"><code><?php echo $val;?></code></td>
										</tr>
										<?php endforeach; ?>
								</tbody>
							</table>
						<?php endforeach; ?>
					<?php endif; ?>

				</div>
			</div>

		</div><!-- /.container -->

		<?php require '../include/footer.php'; ?>
	</body>
</html>
